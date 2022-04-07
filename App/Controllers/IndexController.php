<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{

    public function index()
    {   
        $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
        $this->view->session = isset($_GET['session']) ? $_GET['session'] : '';
        $this->render('index');
        session_start();
        session_destroy();
    }

    public function inscreverse()
    {
        $this->view->user = [
            'name' => '',
            'email' => '',
            'password' => ''
        ];
        $this->view->errorRegister = false;
        $this->render('inscreverse');
    }

    public function register()
    {
        $user = Container::getModel('User');

        $user->__set('name', $user->registerValidation($_POST['name']));
        $user->__set('email', $user->registerValidation($_POST['email']));
        $user->__set('pass', $user->registerValidation(md5($_POST['password'])));
        $emails = $user->getUserEmail();

        $arrayAttributes = [
            $user->__get('name'),
            $user->__get('email'),
            $user->__get('pass')
        ];

        if (!in_array('', $arrayAttributes) && count($emails) === 0) {
            $user->registerUser();
            $this->render('cadastro');
        }

        $this->view->user = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];

        $this->view->errorRegister = true;
        $this->render('inscreverse');
    }
}
