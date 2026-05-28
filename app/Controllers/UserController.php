<?php

namespace App\Controllers;

use App\Services\UserService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class UserController extends BaseController
{
    // Controller logic here
    protected $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function index()
    {
        $title = 'Users';
        return $this->view('admin/user',compact('title'),'layouts/app');
    }

    public function getData(Request $request)
    {
        $user = $this->userService->getUser($request);
        return $this->json($user,$user['statusCode']);
    }

    public function create(Request $request)
    {
        $user = $this->userService->createUser($request->all());
        return $this->json($user,$user['statusCode']);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userService->updateUser($request->all(), $id);
        return $this->json($user,$user['statusCode']);
    }

    public function destroy($id)
    {
        $user = $this->userService->destroyUser($id);
        return $this->json($user,$user['statusCode']);
    }
}
