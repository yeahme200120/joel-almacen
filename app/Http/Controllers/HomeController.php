<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Solicitud;
use App\Models\UDM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $productos = Producto::select(
            "productos.*",
        )
        ->get();
        $categorias = Categoria::all();
        $unidades =UDM::all();
        $codigo = Producto::max('codigo');
        $codigo++;
        $almacenes =Almacen::all();
        return view('home', compact("productos", "categorias","unidades","codigo","almacenes"));
    }
    public function actualizarProducto($campo, $valor,$id){
        $producto = Producto::find($id);
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }
        switch ($campo) {
            case 'Stock Minimo':
                $producto->stock_minimo = $valor;
            break;
            case 'Inventario':
                $producto->inventario = $valor;
            break;
            case 'Extra':
                if ($producto->extra < $valor) {
                    return redirect()->back()->with('error', 'La cantidad de salida no puede ser mayor a la cantidad de entrada.');
                }else{
                    $producto->extra = $valor;
                }
            break;
            default:
            break;
        }
        if($producto->save()){
            return redirect()->back()->with('success', 'Valor actualizado correctamente.');
        }else{
            return redirect()->back()->with('error', 'Error al actualizar el valor.');
        }

    }
    public function registrarProducto(){
        $productos = Producto::all();
        return view("registroProducto", compact("productos"));
    }
    public function registerProduct(Request $request){
        $request->validate([
            'codigo' => 'required',
            'descripcion' => 'required|string|max:255',
            'stock_minimo' => 'required|min:1',
            'inventario' => 'required|min:1',
        ], [
            'codigo.required' => 'El código del producto es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'stock_minimo.required' => 'Debes ingresar un stock mínimo.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo o menor a 1.',
            'inventario.required' => 'Debes ingresar el Inventario.',
            'inventario.min' => 'El stock mínimo no puede ser negativo o menor a 1.',
        ]);
        try {
            $producto = new Producto();
            $producto->fecha = now();
            $producto->codigo = $request->codigo ? $request->codigo : 0;
            $producto->descripcion = $request->descripcion ? $request->descripcion : '';
            $producto->id_udm = $request->unidad ? $request->unidad : '';
            $producto->id_categoria = $request->categoria ? $request->categoria : '';
            $producto->id_almacen = $request->almacen ? $request->almacen : '';
            $producto->stock_minimo = $request->stock_minimo ? $request->stock_minimo : 0;
            $producto->inventario = $request->inventario ? $request->inventario : 0;
            $producto->estatus = 1;
            if($producto->save()){
                return redirect('/home')->with('success', 'Producto registrado correctamente.');
            }else{
                return redirect('/home')->with('error', 'Ocurrió un error al registrar el producto: ');
            }
        } catch (\Throwable $th) {
            return redirect('/home')->with('error', 'Ocurrió un error al registrar el producto: ' . $th->getMessage());
        }
    }
    public function eliminarProducto($id){
        $producto = Producto::find($id);
        if (!$producto) {
            return redirect()->back()->with('error', 'El producto no existe.');
        }
        if($producto->delete()){
            return redirect()->back()->with('success', 'El producto eliminado de manera correcta 😊.');
        }else{
            return redirect()->back()->with('error', 'Error al eliminar el producto.');
        }
    }
    public function pedidos(){
        $fecha = date('Y-m-d');
        //dd(auth::user()->id);
        $productos = Producto::all();
        $unidades = UDM::all();
        $categorias = Categoria::all();
        $solicitudes = Solicitud::join("productos as p","p.id","=","solicituds.producto")
        ->where("id_user","=",auth::user()->id)
        ->where("solicituds.fecha","=",$fecha)
        ->select("solicituds.*", "p.descripcion as producto_nombre")
        ->get();
        return view("pedidos", compact("productos","unidades", "categorias","solicitudes"));
    }
    public function agregarProducto(Request $request){
        dd($request);
        $request->validate([
            'producto' => 'required',
            'unidad' => 'required',
            'cantidad' => 'required',
            'id_user' => 'required',
            'usuario' => 'required',
            'comentarios' => 'required',
            'fecha' => 'required',
        ], [
            'producto.required' => 'El producto es obligatorio.',
            'unidad.required' => 'El campo unidad es un campo obligatorio',
            'cantidad.required' => 'El campo cantidad es un campo obligatorio',
            'id_user.required' => 'El campo id_user es un campo obligatorio',
            'usuario.required' => 'El campo usuario es un campo obligatorio',
            'comentarios.required' => 'El campo comentarios es un campo obligatorio',
            'fecha.required' => 'El campo fecha es un campo obligatorio',
        ]);
        try {
            $solicitud = new Solicitud();
            $solicitud->producto = $request->producto ? $request->producto : '';
            $solicitud->unidad = $request->unidad ? $request->unidad : '';
            $solicitud->cantidad = $request->cantidad ? $request->cantidad : 0;
            $solicitud->id_user = $request->id_user ? $request->id_user : 0;
            $solicitud->usuario = $request->usuario ? $request->usuario : '';
            $solicitud->comentarios = $request->comentarios ? $request->comentarios : '';
            $solicitud->fecha = now();

            if($solicitud->save()){
                return redirect('/home')->with('success', 'Producto registrado correctamente.');
            }else{
                return redirect('/home')->with('error', 'Ocurrió un error al registrar el producto: ');
            }
        } catch (\Throwable $th) {
            return redirect('/home')->with('error', 'Ocurrió un error al registrar el producto: ' . $th->getMessage());
        }
    }
    public function getExistencia($producto){
        $producto = Producto::find($producto);
        return $producto;
    }
    public function agregarSolicitud(Request $request){

        if($request->inventario < $request->cantidad){
            return redirect()->back() ->withErrors(['cantidad' => 'El campo de cantidad no puede ser mayor que el inventario.'])->withInput();
        }

        $request->validate([
            'producto' => 'required',
            'inventario'=>'required',
            'unidad' => 'required',
            'cantidad' => 'required:',
            'comentarios' => 'required',
        ], [
            'producto.required' => 'El producto es obligatorio.',
            'inventario.required' => 'El campo inventario es requerido. Selecciona un producto.',
            'unidad.required' => 'El campo unidad es un campo obligatorio',
            'cantidad.required' => 'El campo cantidad es un campo obligatorio',
            'comentarios.required' => 'El campo comentarios es un campo obligatorio',
        ]);
        try {
            $producto = Producto::find($request->producto_id);
        } catch (\Throwable $th) {  
            dd($th);
        }
        try {
            $solicitud = new Solicitud();
            $solicitud->producto = $request->producto ? $request->producto : '';
            $solicitud->unidad = $request->unidad ? $request->unidad : '';
            $solicitud->cantidad = $request->cantidad ? $request->cantidad : 0;
            $solicitud->id_user = Auth::user()->id ? Auth::user()->id : 0;
            $solicitud->usuario = Auth::user()->name ? Auth::user()->name : '';
            $solicitud->comentarios = $request->comentarios ? $request->comentarios : '';
            $solicitud->fecha = now();
            $solicitud->estatus = 1;

            if($solicitud->save()){
                $producto->inventario = ($producto->inventario ? $producto->inventario : 0) - ($request->cantidad ? $request->cantidad : 0);
                if($producto->save()){
                    return back()->with('success', 'Producto registrado correctamente.');
                } 
                return redirect('/home')->with('error', 'Error al actualizar el inventario');
            }else{
            }
        } catch (\Throwable $th) {
            return redirect('/home')->with('error', 'Ocurrió un error al registrar el producto: ' . $th->getMessage());
        }
    }
}
