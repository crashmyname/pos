<?php
namespace App\Services;

use App\Models\User;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Hash;
use Bpjs\Framework\Helpers\TablePlus;
use Bpjs\Framework\Helpers\Validator;

class UserService
{
    public function register(array $data): User
    {
        Validator::make($data, [
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function login(string $username, string $password): ?User
    {
        $user = User::query()->where('username', $username)->first();
        if ($user && Hash::verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function getUser($request)
    {
        if(!$request->isAjax()){
            return redirect('admin/user');
        }
        return TablePlus::of('users')
                        ->select('username','name','role','id')
                        ->searchable([
                            'username',
                            'name',
                            'role'
                        ])
                        ->filters($request->input('filters',[]) ?? [])
                        ->orderBy('id', 'DESC')
                        ->paginate($request->per_page ?? 10, $request->page ?? 1)
                        ->handleDistinct($request->distinct ?? null)
                        ->make();
    }

    public function createUser(array $data)
    {
        $user = new User();
        if($user->query()->where('username','=',$data['username'])->exists()){
            return [
                'status' => false,
                'statusCode' => 400,
                'message' => 'Username already exists'
            ];
        }
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->password = Hash::make($data['password']);
        $user->role = $data['role'] ?? 'cashier';
        $user->save();
        return [
            'statusCode' => 201,
            'message' => 'User created',
            'data' => $user
        ];
    }

    public function updateUser(array $data, int $id)
    {
        $user = User::find($id);
        if(!$user){
            return null;
        }

        $user->username = $data['username'] ?? $user->username;
        $user->name = $data['name'] ?? $user->name;
        if(isset($data['password'])){
            $user->password = Hash::make($data['password']);
        }
        $user->role = $data['role'] ?? $user->role;
        $user->save();
        return [
            'statusCode' => 200,
            'message' => 'User updated',
            'data' => $user
        ];
    }

    public function destroyUser(int $id)
    {
        $user = User::find($id);
        if(!$user){
            return [
                'status' => false,
                'statusCode' => 404,
                'message' => 'User not found'
            ];
        }
        $user->delete();
        return [
            'statusCode' => 200,
            'message' => 'User deleted',
            'data' => $user
        ];
    }
}
