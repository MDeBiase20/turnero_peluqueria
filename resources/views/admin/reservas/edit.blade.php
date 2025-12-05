@extends('adminlte::page')

@section('title', 'Actualización de reservas')

@section('content_header')
    <h1>Actualización de turnos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><b>Llene los campos</b></h3>
                <!-- /.card-tools -->
                </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ url('admin/reservas/'.$reserva->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Escriba aqui..." name="nombre" value="{{ $usuario->name }}" required>
                                            </div>
                                            @error('nombre')
                                                <small style="color: red">{{ $message }}</small>    
                                            @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="servicio">Servicio</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-card-heading"></i></span>
                                                <select name="servicio" id="" class="form-control" required>
                                                    <option value="" disabled selected>-- Seleccione un servicio --</option>
                                                    @foreach ($servicios as $servicio)
                                                        <option value="{{ $servicio->id }}" {{ $reserva->servicio_id == $servicio->id ? 'selected' : '' }}>{{ $servicio->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('servicio')
                                                <small style="color: red">{{ $message }}</small>    
                                            @enderror
                                    </div>
                                </div>  

                            </div>

                            <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha">Fecha</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar2-day-fill"></i></span>
                                                    <input type="date" id="fecha" class="form-control" name="fecha" value="{{ $reserva->fecha_reserva }}" required>
                                                </div>
                                                @error('fecha')
                                                    <small style="color: red">{{ $message }}</small>    
                                                @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Horarios disponibles</label>
                                            <!-- Contenedor donde se generan los botones -->
                                            <div id="contenedor-horarios" class="d-flex flex-wrap gap-2">
                                                
                                            </div>

                                            <input type="hidden" name="hora_reserva" id="hora_reserva" value="{{ $reserva->hora_reserva ?? '' }}">

                                        </div>
                                    </div>
                            </div>

                            <div class="row">
                                    
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo">Comprobante de pago</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-image"></i></span>
                                                    </div>
                                                    <input type="file" id="file" name="comprobante_pago" class="form-control" name="foto" accept=".pdf">
                                                </div>
                                            @error('foto')
                                                <small style="color:red;"> {{ $message }} </small>
                                            @enderror
                                            <!--Script para previsualizar la imagen a cargar en la base de datos-->
                                            <div class="text-center mt-2">
                                                <output style="padding= 10px" id="list">
                                                    @if (isset($reserva->comprobante_pago))
                                                        <embed src="{{ asset('storage/' . $reserva->comprobante_pago) }}"
                                                            type="application/pdf"
                                                            width="300px"
                                                            height="300px">
                                                    @endif
                                                </output>
                                            </div>    
                                        </div>
                                </div> 
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Referencia celular</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-telephone-fill"></i></span>
                                                <input type="text" class="form-control" placeholder="Ingrese un número de teléfono" name="ref_celular" value="{{ $reserva->ref_celular }}" required>
                                            </div>
                                            @error('ref_celular')
                                                <small style="color: red">{{ $message }}</small>    
                                            @enderror
                                    </div>
                                </div>
                                
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                        <button type="submit" class="btn btn-success">Actualizar</button>
                                        <a href="{{ url('admin/reservas') }}" class="btn btn-secondary">Volver</a>
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
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

            <!----Script para mostrar los horarios disponibles---->
        <script>
            document.getElementById('fecha').addEventListener('change', function() {
                const fecha = this.value;

                fetch(`{{ url('/api/horarios') }}/${fecha}`, {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {

                    let cont = document.getElementById('contenedor-horarios');
                    cont.innerHTML = "";

                    data.horarios.forEach(item => {

                        let btn = document.createElement("button");
                        btn.type = "button"; // Evita que el botón envíe el formulario al hacer clic
                        btn.classList.add("btn", "m-1");
                        btn.innerText = item.hora;

                        if (item.disponible) {
                            btn.classList.add("btn-success");
                            btn.onclick = (event) => seleccionarHora(item.hora, event);
                        } else {
                            btn.classList.add("btn-secondary");
                            btn.disabled = true;
                        }

                        // Marcar si es la hora previamente seleccionada
                        if(document.getElementById('hora_reserva').value === item.hora){
                            btn.classList.add("btn-success");
                        }

                        cont.appendChild(btn);
                    });
                })
                .catch(err => console.error(err));
            });

                // --------------------------
                // FUNCIÓN PARA GUARDAR LA HORA
                // --------------------------
                function seleccionarHora(hora, event) {
                    // Guardar la hora en el input hidden
                    document.getElementById('hora_reserva').value = hora;

                    // Quitar marca previa
                    document.querySelectorAll('#contenedor-horarios button').forEach(btn => {
                        btn.classList.remove("btn-primary");
                    });

                    // Marcar el botón actual
                    event.target.classList.add("btn-primary");
                    
                }

                // Disparar el evento change al cargar la página si ya hay una fecha seleccionada
                document.addEventListener("DOMContentLoaded", function () {
                    const fechaInput = document.getElementById('fecha');
                    if (fechaInput.value) fechaInput.dispatchEvent(new Event('change'));
                });

        </script>
@stop