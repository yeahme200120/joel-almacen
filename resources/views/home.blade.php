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
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center"><b>{{ __('CONTROL DE INVENTARIOS') }}</b></div>

                    <div class="card-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row text-center">
                                    <h1>GRUPO FUNERARIO SIPREF</h1>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-6">
                                        <h3>
                                            Lista de materiales
                                        </h3>
                                    </div>
                                    <div class="col-12 col-md-4 text-center">
                                        <div class="row">
                                            <div class="col-6">
                                                <a href="/pedidos" class="btn btn-warning text-white">Solicitudes</a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-info text-white" data-toggle="modal"
                                                    data-target="#registro"><b>+</b></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mt-3 mb-3 table-responsive">
                                    <table class="table table-hover table-bordered" id="tablaAlmacen">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">#</th>
                                                <th scope="col" class="text-center">Código</th>
                                                <th scope="col" class="text-center">Descricpión</th>
                                                <th scope="col" class="text-center">UDM</th>
                                                <th scope="col" class="text-center">Categoría</th>
                                                <th scope="col" class="text-center">Almacén</th>
                                                <th scope="col" class="text-center">Stock Minimo</th>
                                                <th scope="col" class="text-center">Inventario</th>
                                                <th scope="col" class="text-center">Solicitar</th>
                                                <th scope="col" class="text-center">Acciones</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($productos as $producto)
                                                <tr>
                                                    <th scope="row">{{ $producto->id }}</th>
                                                    <td class="text-center">{{ $producto->codigo }}</td>
                                                    <td class="text-center">{{ $producto->descripcion }}</td>
                                                    <td class="text-center">{{ $producto->id_udm }}</td>
                                                    <td class="text-center">{{ $producto->id_categoria }}</td>
                                                    <td class="text-center">{{ $producto->id_almacen }}</td>
                                                    @if (auth()->user()->id == 1)
                                                        <td
                                                            onclick="mostrarModal({{ $producto->stock_minimo }},'Stock Minimo',{{ $producto->id }})">
                                                            {{ $producto->stock_minimo }}</td>
                                                        <td
                                                            onclick="mostrarModal({{ $producto->inventario }},'Inventario',{{ $producto->id }})">
                                                            {{ $producto->inventario }}</td>
                                                        <td
                                                            class="{{ $producto->inventario <= $producto->stock_minimo ? 'bg-warning' : 'bg-success text-white' }}">
                                                            {{ $producto->inventario <= $producto->stock_minimo ? 'Solicitar Material' : 'Hay suficiente' }}
                                                        </td>
                                                    @else
                                                        <td class="text-center">{{ $producto->stock_minimo }}</td>
                                                        <td class="text-center">{{ $producto->inventario }}</td>
                                                        <td
                                                            class="{{ $producto->inventario <= $producto->stock_minimo ? 'bg-warning' : 'bg-success text-white' }}">
                                                            {{ $producto->inventario <= $producto->stock_minimo ? 'Solicitar Material' : 'Hay suficiente' }}
                                                        </td>
                                                    @endif
                                                    <td class="text-center text-danger">
                                                      <a href="/eliminarProducto/{{$producto->id}}">
                                                          <i class="bi bi-trash3-fill"></i>
                                                      </a>
                                                    </td>
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
    <div class="modal fade" id="registro" tabindex="-1" role="dialog" aria-labelledby="registroTitle" aria-hidden="true">
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
                                    <label for="codigo">Código</label>
                                    <input type="text" name="codigo"
                                        class="form-control @error('codigo') is-invalid @enderror" id="codigo"
                                        placeholder="000" value="{{ $codigo }}">
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

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
                                        class="form-control @error('unidad') is-invalid @enderror">
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
                                    <label for="almacen">Almacén</label>
                                    <select name="almacen" id="almacen"
                                        class="form-control @error('categoria') is-invalid @enderror">
                                        @foreach ($almacenes as $almacen)
                                            <option value="{{ $almacen->almacen }}"
                                                {{ old('almacen') == $almacen->almacen ? 'selected' : '' }}>
                                                {{ $almacen->almacen }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('almacen')
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
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => alert.classList.remove(
                        'show')); // Elimina la clase 'show' para ocultarlo
                    setTimeout(() => alert.remove(),
                        4500); // Elimina completamente el DOM después de la transición
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
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });

            function mostrarModal(valor, campo, id_producto) {
                console.log(valor, campo);
                $("#tituloCampo").text(campo);
                $("#campo").val(valor);
                $("#textoCampo").val(campo);
                $("#id_producto").val(id_producto);
                $('#modalAlmacen').modal('show');
            }

            function cerraModal() {
                $("#tituloCampo").empty();
                $("#tituloCampo").text('');
                $("#campo").val('');
                $("#textoCampo").val('');
                $("#id_producto").val('');
                $('#modalAlmacen').modal('hide');
            }

            function actualizarValor() {
                let campo = $("#textoCampo").val()
                let valor = $("#campo").val()
                let id = $("#id_producto").val();
                console.log(campo, valor, id_producto);
                window.location.href = `/actualizarProducto/${campo}/${valor}/${id}`;
            }
        </script>
    @endsection
