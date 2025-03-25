<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/home';
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
    protected function authenticated()
    {
        try {
            if (Auth::user()->id_rol == 1) {
                return redirect('/home'); // Redirigir a los administradores
            } elseif (Auth::user()->id_rol == 2) {
                return redirect('/pedidos'); // Redirigir a los usuarios normales
            } else {
                return redirect('/pedidos'); // Ruta por defecto
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
