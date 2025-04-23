<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->setAutoRoute(true);
// $routes->setTranslateURIDashes(true);

$routes->get('/', 'Home::getDashboard');

$routes->post('telegram/webhook', 'Telebot::webhook');
