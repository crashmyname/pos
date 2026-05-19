<?php

namespace App\Controllers;

use Bpjs\Framework\Helpers\Auth;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class AuthController extends BaseController
{
    // Controller logic here
    public function index()
    {
        $title = 'Login';
        return $this->view('auth/cashier-login',compact('title'));
    }

    public function loginCashier(Request $request)
    {

    }

    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
