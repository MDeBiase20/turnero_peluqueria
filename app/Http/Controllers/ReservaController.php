<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = auth()->user();
        $reservas = Reserva::where('user_id', $usuario->id)->get();
        return view('admin.reservas.index', compact('reservas', 'usuario'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Traemos el usuario autenticado
        $usuario = auth()->user();
        $servicios = Servicio::all();
        return view('admin.reservas.create', compact('usuario', 'servicios'));
    }

    public function horariosPorFecha($fecha){

        //Array con horarios disponibles
        $horariosDisponibles = [
            '09:00', '09:30',
            '10:00', '10:30',
            '11:00', '11:30',
            '12:00',
            '16:00', '16:30',
            '17:00', '17:30',
            '18:00', '18:30',
            '19:00', '19:30',
        ];

        //Buscamos el servicio de alisado por su nombre
        $servicioAlisado = Servicio::where('nombre', 'Alisado')->value('id');

        if(!$servicioAlisado){
            return response()->json(['error' => 'Servicio de alisado no encontrado'], 404);
        }

        //Comparamos la cántidad de reservas de alisado en la fecha seleccionada
        $cantidadAlisadosReservados = Reserva::where('servicio_id', $servicioAlisado)
                                        ->whereDate('fecha_reserva', $fecha)
                                        ->count();

        //Contamos la cántidad de reservas en el día seleccionado (máximo 4 reservas por día)
        $cantidadTurnos = Reserva::whereDate('fecha_reserva', $fecha)
                            ->count();
                            
        // Si tiene 2 alisados → día bloqueado
        if ($cantidadAlisadosReservados >= 2) {
            return response()->json([
                'horarios' => array_map(fn($h) => [
                    'hora' => $h,
                    'disponible' => false
                ], $horariosDisponibles),
                'motivo' => 'Turnos completos'
            ]);
        }

        // Si hay 4 reservas en total → día bloqueado
        if ($cantidadTurnos >= 4) {
            return response()->json([
                'horarios' => array_map(fn($h) => [
                    'hora' => $h,
                    'disponible' => false
                ], $horariosDisponibles),
                'motivo' => 'cupos_completos'
            ]);
        }

        //Buscamos los turnos reservados para la fecha seleccionada
        $turnosReservados = Reserva::whereDate('fecha_reserva', $fecha)
                            ->pluck('hora_reserva')
                            ->map(function($hora){
                                return substr($hora, 0, 5); // Extraemos solo la parte de hora y minuto (HH:MM) conviente 16:00:00 a 16:00
                            })
                            ->toArray();

        //Array final
        $horariosFinales = [];

        foreach($horariosDisponibles as $horario){
            $horariosFinales[] = [
                'hora' => $horario,
                'disponible' => !in_array($horario, $turnosReservados)
            ];
        }

        return response()->json(['horarios' => $horariosFinales]);
    }


    public function store(Request $request)
    {
        // $datos = $request->all();
        // return response()->json($datos);

        $request->validate([
            'nombre' => 'required',
            'fecha' => 'required|date',
            'hora_reserva' => 'required',
            'servicio' => 'required',
            'comprobante_pago' => 'required|max:2048|mimes:pdf',
            'ref_celular' => 'required',
        ]);

         // Convertir nombre del servicio a ID
        $servicioSeleccionado = Servicio::find($request->servicio);

        if (!$servicioSeleccionado) {
            return back()
                ->with('mensaje', 'El servicio seleccionado no existe.')
                ->with('icono', 'error');
        }

        $servicioId = $servicioSeleccionado->id;

        //Obtenemos el ID del servicio seleccionado (alisado)
        $alisadoId = Servicio::where('nombre', 'Alisado')->value('id');

        if(!$alisadoId){
            return redirect()->back()
                ->with('mensaje', 'El servicio de Alisado no está disponible en este momento.')
                ->with('icono', 'error')
                ->withInput();
        }

        //Cántidad de alisados por día
        $cantidadAlisadosPorDia = Reserva::where('servicio_id', $alisadoId)
                                    ->whereDate('fecha_reserva', $request->input('fecha'))
                                    ->count();

        //Validación para que no se puedan reservar más de 2 alisados por día
        if($servicioId == $alisadoId && $cantidadAlisadosPorDia >= 2){
            return redirect()->back()
                ->with('mensaje', 'No se pueden reservar más de 2 alisados por día.')
                ->with('icono', 'error')
                ->withInput();
        }

        //Cántidad de turnos totales por día
        $cantidadTurnosPorDia = Reserva::whereDate('fecha_reserva', $request->input('fecha'))
                                ->count();
                                
        if($cantidadTurnosPorDia >= 4){
            return redirect()->back()
                ->with('mensaje', 'No se pueden reservar más de 4 turnos por día.')
                ->with('icono', 'error')
                ->withInput();
        }

        //Obtenemos los datos de la fecha, hora y el usuario autenticado
        $fecha = Carbon::parse($request->input('fecha'));
        $hora = $request->input('hora');
        $user = auth()->user();

        //Verificamos que la fecha no sea en el pasado
        if($fecha->isBefore(Carbon::now())){
            return redirect()->back()
                ->with('mensaje', 'No se puede registrar un turno en fechas pasadas.')
                ->with('icono', 'error')
                ->withInput();
        }else{

                //Creamos la reserva
                $reserva = new Reserva();
                $reserva->user_id = $user->id;
                $reserva->fecha_reserva = $request->input('fecha');
                $reserva->hora_reserva = $request->input('hora_reserva');
                $reserva->servicio_id = $request->input('servicio');
                $reserva->ref_celular = $request->input('ref_celular');
                $reserva->estado = 'Confirmado';

                    //Manejamos la subida del comprobante de pago
                    if($request->hasFile('comprobante_pago')){
                        $reserva->comprobante_pago = $request->file('comprobante_pago')->store('comprobantes', 'public');
                    }
                }

                $reserva->save();

                return redirect()->route('admin.reservas.index')
                    ->with('mensaje', 'Reserva creada exitosamente.')
                    ->with('icono', 'success');
            
    }


    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);
        $servicios = Servicio::all();
        $usuario = Auth::user();

         //Verificar que el usuario autenticado sea el propietario de la reserva o un administrador
        return view('admin.reservas.edit', compact('reserva', 'servicios', 'usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $datos = $request->all();
        // return response()->json($datos);

        $request->validate([
            'nombre' => 'required',
            'fecha' => 'required|date',
            'hora_reserva' => 'required',
            'servicio' => 'required',
            'comprobante_pago' => 'nullable|max:2048|mimes:pdf',
            'ref_celular' => 'required',
        ]);

        $reserva = Reserva::findOrFail($id);
        $reserva->fecha_reserva = $request->input('fecha');
        $reserva->hora_reserva = $request->input('hora_reserva');
        $reserva->servicio_id = $request->input('servicio');
        $reserva->ref_celular = $request->input('ref_celular');
        
        //Si la foto se actualiza, se elimina la anterior y se guarda la nueva
        if($request->hasFile('comprobante_pago')){
            //Eliminar el archivo anterior si existe
            if($reserva->comprobante_pago && Storage::disk('public')->exists($reserva->comprobante_pago)){
                Storage::disk('public')->delete($reserva->comprobante_pago);
            }
            //Guardar el nuevo archivo
            $reserva->comprobante_pago = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }

        $reserva->save();

        return redirect()->route('admin.reservas.index')
            ->with('mensaje', 'Reserva actualizada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);

        //Eliminar el archivo de comprobante de pago si existe
        if($reserva->comprobante_pago && Storage::disk('public')->exists($reserva->comprobante_pago)){
            Storage::disk('public')->delete($reserva->comprobante_pago);
        }
        $reserva->delete();
        return redirect()->route('admin.reservas.index')
            ->with('mensaje', 'Reserva eliminada exitosamente.')
            ->with('icono', 'success');
    }
}
