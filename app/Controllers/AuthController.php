<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Bpjs\Framework\Helpers\Auth;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Response;
use Bpjs\Framework\Helpers\Session;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    // Controller logic here
    protected $authService;
    public function __construct()
    {
        $this->authService = new AuthService(); 
    }
    public function index()
    {
        $title = 'Login';
        return $this->view('auth/cashier-login',compact('title'));
    }

    public function indexAdmin()
    {
        $title = 'Admin Login';
        return $this->view('auth/admin-login',compact('title'));
    }

    public function loginCashier(Request $request)
    {
        $login = $this->authService->loginCashier($request->all());
        return $this->json($login,$login['statusCode']);
    }

    public function loginAdmin(Request $request)
    {
        $login = $this->authService->loginAdmin($request->all());
        return $this->json($login,$login['statusCode']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }

    public function onLogin(Request $request)
    {
        $credentials = [
            'identifier' => $request->username,
            'password' => $request->password
        ];

        $user = User::query()->where('username', '=', $request->username)->first();
        if (!$user) {
            return Response::json([
                'status' => 404,
                'message' => 'Username tidak ditemukan'
            ], 404);
        }

        if (Auth::attempt($credentials)) {
            $payload = [
                'iss' => 'koperasi-stanley',
                'sub' => $user->id,
                'user_id' => $user->id,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24),
                'role' => $user->role,
                'username' => $user->username,
                'name' => $user->name,
                'sign' => hash_hmac('SHA256',$user->id.env('JWT_SECRET'),env('JWT_SECRET'))
            ];

            $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
            cookie_set('token', $jwt, 60 * 24, true, false, 'Strict');
            Session::set('user', $user);

            return Response::json([
                'status' => 200,
                'message' => 'Berhasil login',
                'data' => [
                    'token' => $jwt,
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'role' => $user->role,
                        'name' => $user->name,
                    ],
                ]
            ], 200);
        }

        return Response::json(['status' => 401, 'message' => 'Username atau password salah'], 401);
    }
}
