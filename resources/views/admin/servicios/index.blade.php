@extends('adminlte::page')

@section('title', 'Servicios')

@section('content_header')
    <h1><b>Listado de Servicios</b></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Servicios registradas</h3>
                <!-- /.card-tools -->

                    <div class="card-tools">
                            @if (in_array($usuario->role, ['admin', 'super_admin']))
                                <a href="{{ url('admin/servicios/create') }}" class="btn btn-primary">Crear nuevo</a>
                            @endif
                    </div>

                </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table-servicios" class="table table-bordered table-striped table-hover table-sm display nowrap">
                            <thead>
                                <tr>
                                    <th style="text-align: center">#</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Valor</th>

                                    @if (in_array($usuario->role, ['admin', 'super_admin']))
                                        <th style="text-align: center">Acciones</th>
                                    @endif
                                </tr>
                            </thead>

                            @php
                                $contador_servicio = 1;
                            @endphp

                            <tbody>
                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td style="text-align: center">{{ $contador_servicio++ }}</td>
                                        <td style="text-align: center">{{ $servicio->nombre }}</td>
                                        <td style="text-align: center">{{ $servicio->valor }}</td>

                                        @if (in_array($usuario->role, ['admin', 'super_admin']))
                                        <td style="text-align: center" class="text-center">
                                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                <a href="{{ url('/admin/servicios/edit',$servicio->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                                <form action="{{ url('/admin/servicios',$servicio->id) }}" method="post" 
                                                    onclick="preguntar{{ $servicio->id}} (event)" id='miFormulario{{ $servicio->id }}'>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 0px 3px 3px 0px"><i class="bi bi-trash"></i></button>
                                                </form>
                                                <script>
                                                    function preguntar{{ $servicio->id}}(event) {
                                                        event.preventDefault(); // Evita que se envíe el formulario automáticamente
                                                        var form = document.getElementById('miFormulario{{ $servicio->id }}');
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
                                        @endif

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
            </div>
        </div>
    </div>
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

<script>
    $('#table-turnos').DataTable({ 
                        "pageLength": 5,
                        "responsive": true,
                        "autoWidth": false,
                                "language": {
                                    "emptyTable": "No hay información",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Servicios",
                                    "infoEmpty": "Mostrando 0 a 0 de 0 Servicios",
                                    "infoFiltered": "(Filtrado de _MAX_ total Servicios)",
                                    "infoPostFix": "",
                                    "thousands": ",",
                                    "lengthMenu": "Mostrar _MENU_ Servicios",
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
@stop