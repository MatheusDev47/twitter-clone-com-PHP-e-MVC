<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

    protected function initRoutes()
    {
        $routes['home'] = array(
            'route' => '/',
            'controller' => 'indexController',
            'action' => 'index'
        );

        $routes['inscreverse'] = array(
            'route' => '/inscreverse',
            'controller' => 'indexController',
            'action' => 'inscreverse'
        );

        $routes['registrar'] = array(
            'route' => '/registrar',
            'controller' => 'indexController',
            'action' => 'register'
        );

        $routes['autenticar'] = array(
            'route' => '/autenticar',
            'controller' => 'AuthController',
            'action' => 'authUser'
        );

        $routes['sair'] = array(
            'route' => '/sair',
            'controller' => 'AuthController',
            'action' => 'logoff'
        );

        $routes['timeline'] = array(
            'route' => '/timeline',
            'controller' => 'AppController',
            'action' => 'timeline'
        );

        $routes['tweet'] = array(
            'route' => '/tweet',
            'controller' => 'AppController',
            'action' => 'tweet'
        );

        $routes['delete_tweet'] = array(
            'route' => '/delete_tweet',
            'controller' => 'AppController',
            'action' => 'deleteTweet'
        );

        $routes['who_follow'] = array(
            'route' => '/who_follow',
            'controller' => 'AppController',
            'action' => 'whoFollow'
        );

        $routes['search_user'] = array(
            'route' => '/search_users',
            'controller' => 'AppController',
            'action' => 'searchUsers'
        );

        $routes['action'] = array(
            'route' => '/action',
            'controller' => 'AppController',
            'action' => 'action'
        );


        $this->setRoute($routes);
    }
}
