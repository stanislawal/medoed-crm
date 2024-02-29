<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserHelper;
use App\Http\Controllers\Telegram\LoginNotification;
use App\Models\User;
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
        $isWork = UserHelper::isWork($request->login);

        if (!is_null($isWork) && $isWork == false) {
            return redirect()->back()->with(['error' => 'Доступ запрещен.']);
        }

        $attr = [
            'login' => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($attr)) {
            $request->session()->regenerate();
            // (new LoginNotification())->sendMessage();
            User::on()->where('login', $request->login)->update(['visual_password' =>
                $request->password]);
            return redirect()->route('home');
        } else {
            return redirect()->back()->with(['error' => 'Неверные данные входа']);
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
