<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
       $users =  User::on()->with(['roles'])->orderBy('id', 'desc')->get()->toArray();
//        dd($users);
        return view('user.list_users', [
            'users' => $users,
        ]);

    }

    //Страница создания (нахождения формы) пользователя
    public function create()
    {
        return view('user.user_create');
    }


    public function store(Request $request)
    {
        $attr = [
            'full_name' => $request->full_name,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'contact_info' => $request->contact_info,
            'birthday' => $request->birthday,
            'manager_salary' => $request->manager_salary ?? null,
            'is_work' => true,
        ];

        $user = User::on()->create($attr);
        $user->assignRole($request->role); // выдать роль пользователю
        return redirect()->back()->with(['message' => 'Пользователь успешно добавлен']);
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        return view('user.user_edit', [
            'user' => User::on()->find($id)->toArray()
        ]);
    }


    public function update(UpdateUserRequest $request, $id)
    {
        $attr = collect($request->validated());

        if(is_null($attr['password'])){
            unset($attr['password']);
        }else{
            $attr['password'] = Hash::make($attr['password']);
        }
        $user = User::on()->find($id);

        $user->syncRoles([$attr['role']]); // удалить все роли у пользователя, и назначить новые из массива


        User::on()->where('id', $id)->update($attr->except('role')->toArray());

        return redirect()->back()->with(['success' => 'Message']);
    }


    public function destroy($user)
    {
        User::on()->where('id', $user)->delete();
        return redirect()->back()->with(['success' => 'Пользователь успешно удален']);
    }
}
