<?php

namespace App\Controllers;

use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class HomeController extends BaseController
{
    // Controller logic here
    public function index()
    {
        $title = 'Home';
        return $this->view('admin/home',compact('title'),'layouts/app');
    }
}
