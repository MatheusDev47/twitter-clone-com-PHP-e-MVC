<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action
{

    public function authUser()
    {
        $user = Container::getModel('User');

        $user->__set('email', htmlspecialchars($_POST['email']));
        $user->__set('pass', htmlspecialchars(md5($_POST['password'])));
        $user->auth();

        if (!empty($user->__get('id')) && !empty($user->__get('name'))) {
            session_start();
            $_SESSION['id'] = $user->__get('id');
            $_SESSION['name'] = $user->__get('name');

            header('Location: /timeline');
        } else {
            header('Location: /?login=error');
        }
    }

    public function logoff()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
