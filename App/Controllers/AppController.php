<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

    public function authValidation()
    {
        session_start();
        if (!isset($_SESSION['id']) || !isset($_SESSION['name'])) {
            header('Location: /');
        }
    }

    public function getStats () 
    {   
        $user = Container::getModel('User');
        $user->__set('id', $_SESSION['id']);

        $this->view->amountTweets = $user->getAmoutTweets();
        $this->view->amountFollows = $user->getAmountFollows();
    }

    public function timeline()
    {
        $this->authValidation();
        $this->getStats();
        
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_user', $_SESSION['id']);

        //variáveis de páginação
        $amoutRegistersPage = 10;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $displacement = ($page - 1) * $amoutRegistersPage;

        //recupera os registros por páginação
        $this->view->tweets = $tweet->recoveryPage($amoutRegistersPage, $displacement);
        
        //recupera a quantidade dos registros
        $amountTweets = $tweet->allTweets();
        $this->view->amountPages = ceil($amountTweets['amount'] / $amoutRegistersPage);
        $this->view->pageActivated = $page;

        $this->render('timeline');
    }

    public function tweet()
    {
        $this->authValidation();
        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_user', $_SESSION['id']);
        $tweet->__set('tweet', htmlspecialchars($_POST['tweet']));
        $tweet->add();
        header('Location: /timeline');
    }

    public function deleteTweet () 
    {
        $this->authValidation();
        $tweet = Container::getModel('Tweet');
        $id_tweet = isset($_GET['id_tweet']) ? $_GET['id_tweet'] : '';

        $tweet->__set('id', $id_tweet);
        $tweet->delete();
        header('Location: /timeline');
    }

    public function whoFollow()
    {   
        $this->authValidation();
        $this->getStats();

        $name = isset($_GET['name']) ? $_GET['name'] : '';

        $this->view->users = Array();

        if ($name !== '') {
            $user = Container::getModel('User');
            $user->__set('name', htmlspecialchars($name));
            $user->__set('id', $_SESSION['id']);
            $this->view->users = $user->getUsers();
        }

        $this->render('whoFollow');
    }

    public function action () 
    {
        $this->authValidation();

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id_user_follow = isset($_GET['id']) ? $_GET['id'] : '';

        $follows = Container::getModel('Follows');
        $follows->__set('id_user', $_SESSION['id']);

        if($action === 'follow') {
            $follows->follow($id_user_follow);
        }

        if ($action === 'unfollow') {
            $follows->unfollow($id_user_follow);
        }

        header('Location: /who_follow');
    }
}
