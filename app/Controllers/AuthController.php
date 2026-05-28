<?php

namespace App\Controllers;

use App\Services\AuthService;
use Bpjs\Framework\Helpers\Auth;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

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
}
