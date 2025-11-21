@extends('adminlte::page')

@section('title', 'Configuración')

@section('content_header')
    <h1>Configuración de la cuenta</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h3 class="card-title">Datos del usuario</h3>

                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <form action="{{ url('admin/configuracion', $usuario->id) }}" method="post">
                    @csrf
                    @method('PUT')

                        <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre:</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $usuario->name }}" required>
                                @error('nombre')
                                    <small style="color:red;"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Correo:</label>
                                <input type="text" name="correo" class="form-control" value="{{ $usuario->email }}" required>
                                @error('correo')
                                    <small style="color:red;"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Contraseña:</label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                    <small style="color:red;"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Repetir contraseña:</label>
                                <input type="password" name="password_confirmation" class="form-control">
                                @error('password_repeat')
                                    <small style="color:red;"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success"><i class="bi bi-arrow-clockwise"></i> Actualizar</button>
                                    </div>
                                </div>
                    </div>
                </form>

                

              </div>
              <!-- /.card-body -->
            </div>
        </div>
        
    </div>
@stop

@section('css')
    
@stop

@section('js')
    
@stop