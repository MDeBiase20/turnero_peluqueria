<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = auth()->user();
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios', 'usuario'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $datos = $request->all();
        // return response()->json($datos);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'valor' => 'required|numeric',
        ]);

        $servicio = new Servicio();
        $servicio->nombre = $request->nombre;
        $servicio->valor = $request->valor;
        $servicio->save();

        return redirect()->route('admin.servicio.index')
                        ->with('mensaje', 'Servicio creado exitosamente.')
                        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('admin.servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $datos = $request->all();
        // return response()->json($datos);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'valor' => 'required|numeric',
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->nombre = $request->nombre;
        $servicio->valor = $request->valor;
        $servicio->save();

        return redirect()->route('admin.servicio.index')
                        ->with('mensaje', 'Servicio actualizado exitosamente.')
                        ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->route('admin.servicio.index')
                        ->with('mensaje', 'Servicio eliminado exitosamente.')
                        ->with('icono', 'success');
    }
}
