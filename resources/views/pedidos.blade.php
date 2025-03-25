@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center"><b>{{ __('SOLICITUD DE INSUMOS') }}</b></div>

                    <div class="card-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row text-center">
                                    <h1>GRUPO FUNERARIO SIPREF</h1>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <h3>
                                            Productos
                                        </h3>
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <form action="/agregarSolicitud" method="POST" id="solicitudForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-md-6 col-xl-7 js-states">
                                                <label for="">Producto</label>
                                                <select id="selectProducto" class="form-control select2" name="producto">
                                                    @foreach ($productos as $producto)
                                                        <option value="{{ $producto->id }}">{{ $producto->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('producto')
                                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <input type="hidden" class="form-control" name="producto_id" id="producto_id"
                                                value="{{ old('producto_id') }}">
                                            <div class="col-12 col-md-6 col-xl-5">
                                                <label for="">Existencia</label>
                                                <input class="form-control" type="number" value="{{ old('inventario') }}"
                                                    min="0" name="inventario" id="inventario" readonly>
                                                @error('inventario')
                                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <label for="">Unidad</label>
                                                <input class="form-control" type="text" readonly name="unidad"
                                                    id="unidad" value="{{ old('unidad') }}">
                                                @error('unidad')
                                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="">Cantidad</label>
                                                <input class="form-control" type="number" value="{{ old('cantidad') }}"
                                                    min="1" name="cantidad">
                                                @error('cantidad')
                                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label for="">Comentarios</label>
                                                <input class="form-control" type="text" value="{{ old('comentarios') }}"
                                                    name="comentarios">
                                                @error('comentarios')
                                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-5 justify-content-md-center mb-5">
                                            <div class="col-12 col-md-4 text-center">
                                                <button class="btn btn-info text-white"> Solicitar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-3 mb-3 table-responsive">
                                    <table class="table table-hover table-bordered" id="tablaAlmacen">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col" class="text-center">Producto</th>
                                                <th scope="col" class="text-center">Cantidad</th>
                                                <th scope="col" class="text-center">Comentarios</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($solicitudes as $solicitud)
                                                <tr>
                                                    <th scope="row">{{ $solicitud->id }}</th>
                                                    <td class="text-center">{{ $solicitud->producto_nombre }}</td>
                                                    <td class="text-center">{{ $solicitud->cantidad }}</td>
                                                    <td class="text-center">{{ $solicitud->comentarios }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalAlmacen" tabindex="-1" role="dialog" aria-labelledby="modalAlmacen"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header row justify-content-between">
                        <div class="col-12">
                            <h5 class="modal-title" id="exampleModalLongTitle"><b>Actualizar Cantidad <label
                                        id="tituloCampo"></h3></b></h5>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="hidden" name="textoCampo" id="textoCampo">
                        <input class="form-control" type="hidden" name="id_producto" id="id_producto">
                        <input type="number" class="form-control" id="campo" name="campo" min="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="cerraModal()">Close</button>
                        <button type="button" class="btn btn-primary" onclick="actualizarValor()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="registro" tabindex="-1" role="dialog" aria-labelledby="registroTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="exampleModalLongTitle">Registrar Producto Nuevo</h5>
                </div>
                <div class="modal-body">
                    <form action="/registerProduct" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <input type="text" name="descripcion"
                                        class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                        placeholder="Descripción del producto" value="{{ old('descripcion') }}">
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="unidad">Unidad de medida</label>
                                    <select name="unidad" id="unidad"
                                        class="form-control @error('unidad') is-invalid @enderror" readonly>
                                        @foreach ($unidades as $unidad)
                                            <option value="{{ $unidad->unidad }}"
                                                {{ old('unidad') == $unidad->unidad ? 'selected' : '' }}>
                                                {{ $unidad->unidad }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría</label>
                                    <select name="categoria" id="categoria"
                                        class="form-control @error('categoria') is-invalid @enderror">
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->categoria }}"
                                                {{ old('categoria') == $categoria->categoria ? 'selected' : '' }}>
                                                {{ $categoria->categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="stock_minimo">Stock Mínimo</label>
                                    <input type="text" name="stock_minimo" min="1"
                                        class="form-control @error('stock_minimo') is-invalid @enderror" id="stock_minimo"
                                        placeholder="Cantidad mínima" value="{{ old('stock_minimo') }}">
                                    @error('stock_minimo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="inventario">Inventario</label>
                                    <input type="number" min="1" name="inventario"
                                        class="form-control @error('inventario') is-invalid @enderror" id="inventario"
                                        placeholder="Cantidad en inventario" value="{{ old('inventario') }}">
                                    @error('inventario')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row p-5">
                            <button type="submit" class="btn btn-success">Registrar producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                //Select 2 para los filtros
                $('.select2').select2({
                    placeholder: 'Seleccione una opción',
                    allowClear: true
                });

                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 500);
                    });
                }, 2000);
                $('#tablaAlmacen').dataTable({
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay información",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ Entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "Sin resultados encontrados",
                        "paginate": {
                            "first": "Primero",
                            "last": "Ultimo",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    dom: '<"row"<"col-md-6"B><"col-md-6"f>>rtip',
                    buttons: [
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-danger',
                            orientation: 'portrait', // Ajusta el PDF a formato horizontal
                            pageSize: 'A4', // Tamaño de página
                            customize: function(doc) {
                                doc.styles.tableHeader = {
                                    bold: true,
                                    fontSize: 12,
                                    color: 'black'
                                };
                                doc.styles.tableBodyEven = {
                                    alignment: 'left'
                                };
                                doc.defaultStyle.fontSize = 10; // Ajusta el tamaño de fuente
                                doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                    .length + 1).join('*').split('');
                            },
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Imprimir',
                            autoPrint: true,
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ]
                });
            });

            $(document).ready(function() {
                $('#mySelect2').select2();


                $('#selectProducto').on('change', function() {
                    const selectedValue = $(this).val(); // Obtiene el valor seleccionado
                    console.log("Valor seleccionado:", selectedValue);
                    try {
                        $.ajax({
                            url: `/getExistencia/${selectedValue}`,
                            method: "GET",
                            success: function(response) {
                                console.log("Respuesta del servidor:", response);
                                $("#inventario").val(response.inventario)
                                $("#unidad").val(response.id_udm)
                                $("#producto_id").val(response.id)
                            },
                            error: function(xhr, status, error) {
                                console.error("Error al enviar datos:", error);
                            }
                        });
                    } catch (error) {
                        console.log("Error al traer la existencia ", error);

                    }

                });
            });
            $(".js-example-responsive").select2({
                width: 'resolve' // need to override the changed default
            });
        </script>
        <style>
            .dataTables_wrapper .dt-buttons {
                float: left; /* Asegura que los botones estén alineados a la izquierda */
                margin-bottom: 10px; /* Espaciado inferior */
            }
            .dataTables_filter {
                float: right;
                margin-bottom: 10px;
                margin: auto
            }

            .dataTables_filter input {
                width: 120px !important;
            }
        </style>
    @endsection
