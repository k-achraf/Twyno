<?php
namespace App\Controllers\Admin;


use System\T_Controller;

class AuthController extends T_Controller
{

    /**
     * show login form
     * @return mixed
     */
    public function index()
    {
        return $this->view->render('admin/login');
    }
}