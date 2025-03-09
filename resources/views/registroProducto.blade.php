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
                <div class="card-header text-center"><b>{{ __('REGISTRO DE PRODUCTOS') }}</b></div>

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
                                            <button class="btn btn-warning text-white">Registro</button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-info text-white">Instructivo</button>
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
                                            <th scope="col">#</th>
                                            <th scope="col">Código</th>
                                            <th scope="col">Descricpión</th>
                                            <th scope="col">UDM</th>
                                            <th scope="col">Categoría</th>
                                            <th scope="col">Almacén</th>
                                            <th scope="col">Stock Minimo</th>
                                            <th scope="col">Inventario</th>
                                            <th scope="col">Solicitar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productos as $producto)
                                        <tr>
                                            <th scope="row">{{$producto->id}}</th>
                                            <td>{{$producto->codigo}}</td>
                                            <td>{{$producto->descripcion}}</td>
                                            <td>{{$producto->id_udm}}</td>
                                            <td>{{$producto->id_categoria}}</td>
                                            <td>{{$producto->id_almacen}}</td>
                                            @if (auth()->user()->id == 1)
                                            <td onclick="mostrarModal({{$producto->stock_minimo}},'Stock Minimo',{{$producto->id}})">{{$producto->stock_minimo}}</td>
                                            <td onclick="mostrarModal({{$producto->inventario}},'Inventario',{{$producto->id}})">{{$producto->inventario}}</td>
                                            <td class="{{ $producto->inventario <= $producto->stock_minimo ? 'bg-warning' : 'bg-success text-white' }}">{{ $producto->inventario <= $producto->stock_minimo ? "Solicitar Material" : 'Hay suficiente' }}</td>
                                            @else
                                            <td>{{$producto->stock_minimo}}</td>
                                            <td>{{$producto->inventario}}</td>
                                            <td class="{{ $producto->inventario <= $producto->stock_minimo ? 'bg-warning' : 'bg-success text-white' }}">{{ $producto->inventario <= $producto->stock_minimo ? "Solicitar Material" : 'Hay suficiente' }}</td>
                                            @endif
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
  <div class="modal fade" id="modalAlmacen" tabindex="-1" role="dialog" aria-labelledby="modalAlmacen" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header row justify-content-between">
                <div class="col-12">
                    <h5 class="modal-title" id="exampleModalLongTitle"><b>Actualizar Cantidad <label id="tituloCampo"></h3></b></h5>
                </div>
        </div>
        <div class="modal-body">
            <input class="form-control" type="hidden" name="textoCampo" id="textoCampo">
            <input class="form-control" type="hidden" name="id_producto" id="id_producto" >
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
<script>
 $(document).ready(function() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => alert.classList.remove('show')); // Elimina la clase 'show' para ocultarlo
        setTimeout(() => alert.remove(), 150); // Elimina completamente el DOM después de la transición
    }, 2000);
    $('#tablaAlmacen').dataTable( {
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
    } );
} );
function mostrarModal(valor, campo, id_producto){
    console.log(valor,campo);
    $("#tituloCampo").text(campo);
    $("#campo").val(valor);
    $("#textoCampo").val(campo);
    $("#id_producto").val(id_producto);
    $('#modalAlmacen').modal('show');
}
function cerraModal(){
    $("#tituloCampo").empty();
    $("#tituloCampo").text('');
    $("#campo").val('');
    $("#textoCampo").val('');
    $("#id_producto").val('');
    $('#modalAlmacen').modal('hide');
}
function actualizarValor(){
    let campo = $("#textoCampo").val()
    let valor = $("#campo").val()
    let id =  $("#id_producto").val();
    console.log(campo, valor, id_producto);
    window.location.href = `/actualizarProducto/${campo}/${valor}/${id}`;
}
</script>
@endsection
