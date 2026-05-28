<?php

namespace App\Services;
use App\Models\User;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Auth;
use Bpjs\Framework\Helpers\Validator;

class AuthService
{
    // Service logic here
    public function loginCashier(array $data)
    {
        $credentials = [
            'identifier' => $data['username'],
            'password' => $data['password']
        ];
        $user = User::query()->where('username','=',$data['username'])->first();
        if(!$user){
            return [
                'statusCode' => 404,
                'message' => 'Username not found'
            ];
        }
        if(Auth::attempt($credentials)){
            if(Request::isAjax()){       
                return ['statusCode'=>200,'message'=>'Berhasil login'];
            }
            return ['statusCode'=>200,'message'=>'Berhasil login'];
        } else {
            return [
                'statusCode' => 400,
                'message' => 'Username atau password salah'
            ];
        }
    }

    public function loginAdmin(array $data)
    {
        $credentials = [
            'identifier' => $data['username'],
            'password' => $data['password']
        ];
        $user = User::query()->where('username','=',$data['username'])->first();
        if(!$user){
            return [
                'statusCode' => 404,
                'message' => 'Username not found'
            ];
        }
        if($user->role !== 'ADMIN'){
            return [
                'status' => false,
                'statusCode' => 403,
                'message' => 'Unauthorized'
            ];
        }
        if(Auth::attempt($credentials)){
            if(Request::isAjax()){       
                return ['statusCode'=>200,'message'=>'Berhasil login'];
            }
            return ['statusCode'=>200,'message'=>'Berhasil login'];
        } else {
            return [
                'statusCode' => 400,
                'message' => 'Username atau password salah'
            ];
        }
    }
}
