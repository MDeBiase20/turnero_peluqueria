<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        $usuario_id = Auth::check() ? Auth::user()->id : redirect()->route('login')->send();

        // Si no está autenticado, Laravel lo redirige solo gracias al middleware 'auth'
        return view('admin.index');
    }
}
