<?php

namespace App\Http\Controllers\User;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Bank;
use App\Models\User;
use App\Models\UserActive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $users = User::on()->with(['roles']);

        $this->filter($request, $users);

        $users->orderBy('id', 'desc');
        $users = $users->paginate(50);

        $roles = Role::on()->get()->toArray();

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
            'full_name'      => $request->full_name,
            'login'          => $request->login,
            'password'       => Hash::make($request->password),
            'contact_info'   => $request->contact_info,
            'birthday'       => $request->birthday,
            'manager_salary' => $request->manager_salary ?? null,
            'working_day'    => $request->working_day ?? null,
            'link_author'    => $request->link_author ?? null,
            'payment'        => $request->payment ?? null,
            'bank_id'        => $request->bank_id ?? null,

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
            'user'  => User::on()->find($id)->toArray(),
            'banks' => Bank::on()->get(),
            'roles' => Role::on()->get()
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $attr = collect($request->validated());

        if (is_null($attr['password'] ?? null)) {
            unset($attr['password']);
        } else {
            $attr['visual_password'] = $attr['password'];
            $attr['password'] = Hash::make($attr['password']);
        }

        if (!is_null($attr['role'] ?? null)) {
            $user = User::on()->find($id);
            $user->syncRoles([$attr['role']]); // удалить все роли у пользователя, и назначить новые из массива
        }

        User::on()->where('id', $id)->update($attr->except('role')->toArray());

        return redirect()->back()->with(['success' => 'Пользователь успешно обновлен.']);
    }

    public function destroy($user)
    {
        User::on()->where('id', $user)->delete();
        return redirect()->back()->with(['success' => 'Пользователь успешно удален']);
    }

    private function filter($request, &$users)
    {
        $users->when(!empty($request->full_name), function ($where) use ($request) {
            $where->where('full_name', 'like', '%' . $request->full_name . '%');
        });

        $users->when(!empty($request->role), function ($whereHas) use ($request) {
            $whereHas->whereHas('roles', function ($where) use ($request) {
                $where->where('id', $request->role);
            });
        });

        $users->when(!empty($request->sort), function ($where) use ($request) {
            $where->orderBy($request->sort, $request->direction);
        });
    }

    public function userActive()
    {
        UserActive::on()->updateOrCreate([
            'user_id' => UserHelper::getUserId(),
        ], [
            'user_id'   => UserHelper::getUserId(),
            'date_time' => now()
        ]);

        $userActive = UserActive::on()->with('user.roles')
            ->whereBetween('date_time', [now()->subMinutes(3)->format('Y-m-d H:i:s'), now()->format('Y-m-d H:i:s')])
            ->get()
            ->toArray();

        return response()->json([
            'result' => true,
            'html'   => view('NavComponents.UserActive.user_list', ['userActive' => $userActive])->render(),
            'count'  => count($userActive)
        ]);
    }
}
