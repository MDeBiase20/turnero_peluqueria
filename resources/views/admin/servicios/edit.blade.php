@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
    <h1>Servicios</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h3 class="card-title">Datos del servicio</h3>

                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <form action="{{ url('admin/servicios/'. $servicio->id) }}" method="post">
                    @csrf
                    @method('PUT')

                        <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombre:</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $servicio->nombre }}" required>
                                @error('nombre')
                                    <small style="color:red;"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Valor:</label>
                                <input type="number" name="valor" class="form-control" value="{{ $servicio->valor }}" required>
                                @error('valor')
                                    <small style="color:red;"> {{ $message }} </small>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <br>
                    <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="{{ url('admin/servicios') }}" class="btn btn-secondary">Volver</a>
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