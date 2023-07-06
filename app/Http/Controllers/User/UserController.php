<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $users =  User::on()->with(['roles']);

            $this->filter($request, $users);

        $users->orderBy('id', 'desc');
        $users = $users->paginate(50);


       $roles= Role::on()->get()->toArray();
//        dd($users);
        return view('user.list_users', [
            'users' => $users,
            'roles' => $roles,
        ]);

    }

    //Страница создания (нахождения формы) пользователя
    public function create()
    {
        return view('user.user_create', [
            'banks' => Bank::on()->get(),
            'roles' => Role::on()->get()
        ]);
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
            'payment' => $request->payment ?? null,
            'bank_id' => $request->bank_id ?? null,
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
            'user' => User::on()->find($id)->toArray(),
            'banks' => Bank::on()->get(),
            'roles' => Role::on()->get()
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

//$projects->when(!empty($request->manager_id), function ($where) use ($request) {
//            $where->where('manager_id', $request->manager_id);

    private function filter($request, &$users){
        $users->when(!empty($request->role), function ($whereHas) use ($request) {
            $whereHas->whereHas('roles', function ($where) use ($request) {
                $where->where('id', $request->role);
            });
        });
    }

}

