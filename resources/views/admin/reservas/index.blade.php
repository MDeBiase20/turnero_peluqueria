@extends('adminlte::page')

@section('title', 'Reservas')

@section('content_header')
    <h1><b>Mis Reservas</b></h1>
@stop

@section('content')

@if (in_array($usuario->role, ['admin', 'super_admin']))
    <div id='calendar'></div>
@endif

<!-- Modal para las reservas-->
    <div class="modal fade" id="modalTurno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Detalle del turno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="row">
                            <!-- PDF arriba ocupando ancho completo -->
                            <div class="mb-3 text-center">
                                <iframe id="comprobanteTurno"
                                        src=""
                                        style="width: 100%; height: 350px; border:1px solid #ddd; border-radius:8px;"
                                        frameborder="0">
                                </iframe>
                            </div>

                            <div class="col-md-8">
                                <p><strong>Nombre:</strong> <span id="nombreTurno"></span></p>
                                <p><strong>Teléfono:</strong> <span id="telefonoTurno"></span></p>
                                <p><strong>Servicio:</strong> <span id="servicioTurno"></span></p>
                                <p><strong>Fecha y hora:</strong> <span id="fechaTurno"></span></p>
                                <p><strong>Estado:</strong> <span id="estadoTurno"></span></p>
                            </div>
                        </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <!-- Botón para abrir el modal de estado -->
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalEstadoTurno" id="btnCambiarEstado">Cambiar Estado</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Estado -->
        <div class="modal fade" id="modalEstadoTurno" tabindex="-1" aria-labelledby="modalEstadoTurnoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="formCambiarEstado" method="POST" action="{{ url('reservas/cambiar-estado') }}">
                            @csrf
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalEstadoTurnoLabel">Actualizar Estado del Turno</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </div>
                            <div class="modal-body">
                                <input type="hidden" id="reserva_id" name="reserva_id">

                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select form-control" id="estado_turno" name="estado" required>
                                    <option value="Confirmado">Confirmado</option>
                                    <option value="Cancelado">Cancelado</option>
                                    <option value="Finalizado">Finalizado</option>
                                    </select>
                                </div>
                            </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-success">Actualizar</button>
                                </div>
                        </form>    
                        
                    </div>
                </div>
        </div>

@if (in_array($usuario->role, ['cliente']))
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Reservas registradas</h3>
                <!-- /.card-tools -->

                    <div class="card-tools">
                        <a href="{{ url('admin/reservas/create') }}" class="btn btn-primary">Crear nueva reserva</a>
                    </div>

                </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                            <table id="table-reservas" class="table table-bordered table-striped table-hover table-sm display nowrap">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">#</th>
                                        <th style="text-align: center">Nombre</th>
                                        <th style="text-align: center">Servicio</th>
                                        <th style="text-align: center">Fecha y hora</th>
                                        <th style="text-align: center">Acciones</th>
                                    </tr>
                                </thead>

                                @php
                                    $contador_reservas = 1;
                                @endphp

                                <tbody>
                                    @foreach ($reservas as $reserva)
                                        <tr>
                                            <td style="text-align: center">{{ $contador_reservas++ }}</td>
                                            <td style="text-align: center">{{ $reserva->user->name }}</td>
                                            <td style="text-align: center">{{ $reserva->servicio->nombre }}</td>
                                            <td style="text-align: center">{{ $reserva->fecha_reserva. " / ".$reserva->hora_reserva}}</td>
                                            <td style="text-align: center" class="text-center">
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <a href="{{ url('/admin/reservas/edit',$reserva->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                                    <form action="{{ url('/admin/reservas',$reserva->id) }}" method="post" 
                                                        onclick="preguntar{{ $reserva->id}} (event)" id='miFormulario{{ $reserva->id }}'>
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 0px 3px 3px 0px"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                    <script>
                                                        function preguntar{{ $reserva->id}}(event) {
                                                            event.preventDefault(); // Evita que se envíe el formulario automáticamente
                                                            var form = document.getElementById('miFormulario{{ $reserva->id }}');
                                                            Swal.fire({
                                                                title: '¿Estás seguro?',
                                                                text: "¡No podrás revertir esto!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Sí, eliminarlo!'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    form.submit(); // Envía el formulario si el usuario confirma
                                                                }
                                                            });
                                                        }
                                                    </script>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        
                    </div>
                    <!-- /.card-body -->
            </div>
        </div>
    </div>
@endif

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}

    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

<!-- DataTables Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

@stop

@section('js')

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- DataTables Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>

    <script>

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth'
        });
        calendar.render();
    });

    </script>

<script>
    $('#table-reservas').DataTable({ 
                        "pageLength": 5,
                        "responsive": true,
                        "autoWidth": false,
                                "language": {
                                    "emptyTable": "No hay información",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Reservas",
                                    "infoEmpty": "Mostrando 0 a 0 de 0 Reservas",
                                    "infoFiltered": "(Filtrado de _MAX_ total Reservas)",
                                    "infoPostFix": "",
                                    "thousands": ",",
                                    "lengthMenu": "Mostrar _MENU_ Reservas",
                                    "loadingRecords": "Cargando...",
                                    "processing": "Procesando...",
                                    "search": "Buscador:",
                                    "zeroRecords": "Sin resultados encontrados",
                                    "paginate": {
                                        "first": "Primero",
                                        "last": "Ultimo",
                                        "next": "Siguiente",
                                        "previous": "Anterior"
                                    }
                                },
                            })
    
</script>

{{-- Script para mostrar desde el calendario los turnos --}}
<script>
    document.addEventListener('DOMContentLoaded',function(){
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            initialView: 'dayGridMonth',
            events: '{{ url("/reservas/calendario") }}',

            eventClick: function(info){
                
                //Evitar redirección por defecto
                info.jsEvent.preventDefault();

                //Lenamos los datos del modal
                document.getElementById('nombreTurno').textContent = info.event.extendedProps.nombre;
                document.getElementById('telefonoTurno').textContent  = info.event.extendedProps.ref_celular;
                document.getElementById('servicioTurno').textContent  = info.event.extendedProps.servicio;
                document.getElementById('fechaTurno').textContent  = info.event.start.toLocaleString();
                document.getElementById('estadoTurno').textContent  = info.event.extendedProps.estado;

                    // Mostrar comprobante de pago
                    let comprobante = info.event.extendedProps.comprobante_pago;
                    
                    if (comprobante) {
                        document.getElementById('comprobanteTurno').src = '/turneropeluqueria/public/storage/' + comprobante;
                    } else {
                        document.getElementById('comprobanteTurno').src = '';
                    }

                    // Guardamos el id del turno para el modal de estado
                    document.getElementById('reserva_id').value = info.event.id;
                    document.getElementById('estado_turno').value = info.event.extendedProps.estado ?? 'Confirmado';

                    // 👉 Mostrar modal principal
                    $('#modalTurno').modal('show');

                    // //Modal del estado
                    // document.getElementById('btnCambiarEstado').onclick = function(){
                    //     var modalEstado = new bootstrap.Modal(document.getElementById('modalEstadoTurno'));
                    //     modalEstado.show();
                    // }
            }
        })

        calendar.render();
    })
</script>

@stop