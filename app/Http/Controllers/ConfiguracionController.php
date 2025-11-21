<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $usuario = auth()->user();
        return view('admin.configuracion.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // $datos = $request->all();
        // return response()->json($datos);

        //Obtenemos el usuario autenticado
        $usuario = Auth()->user();

        //Validamos los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|confirmed',
        ]);

        //Comparamos la contraseña con la confirmación
        if ($request->filled('password')) {

            if($request->input('password') !== $request->input('password_confirmation')) {
                return redirect()->back()
                ->withErrors(['password' => 'La confirmación de la contraseña no coincide.'])
                ->withInput();
            }else{
                //Actualizamos la contraseña
                $usuario->password = bcrypt($request->input('password'));
            }
        }

        //Actualizamos los datos del usuario
        $usuario->name = $request->input('nombre');
        $usuario->email = $request->input('correo');
        $usuario->save();

        return redirect()->back()
                        ->with('mensaje', 'Configuración actualizada correctamente.')
                        ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }
}
