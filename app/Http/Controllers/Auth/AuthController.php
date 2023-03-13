<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Telegram\LoginNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function telegramSend()
    {

    }

    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $attr = [
            'login' => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($attr)) {
            $request->session()->regenerate();

            (new LoginNotification())->sendMessage();

            return redirect()->route('home');
        } else {
            return redirect()->back()->with(['error' => 'Неверные данные аутентификации']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login.index');
    }

}
