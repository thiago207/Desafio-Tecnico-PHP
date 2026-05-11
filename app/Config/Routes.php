<?php
// app/Config/Routes.php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── Autenticação ──────────────────────────────────────────
$routes->get('/',               'AuthController::login');
$routes->get('login',           'AuthController::login');
$routes->post('login',          'AuthController::doLogin');
$routes->get('cadastro',        'AuthController::cadastro');
$routes->post('cadastro',       'AuthController::doCadastro');
$routes->get('logout',          'AuthController::logout');

// ── Atividades (requerem sessão) ──────────────────────────
$routes->get('agenda',                      'AtividadeController::index');
$routes->get('agenda/calendario',           'AtividadeController::calendario');

// CRUD via AJAX (retornam JSON)
$routes->post('atividade/store',            'AtividadeController::store');
$routes->get('atividade/(:num)',            'AtividadeController::show/$1');
$routes->post('atividade/update/(:num)',    'AtividadeController::update/$1');
$routes->post('atividade/destroy/(:num)',   'AtividadeController::destroy/$1');
$routes->post('atividade/status/(:num)',    'AtividadeController::updateStatus/$1');

// Eventos do calendário (JSON)
$routes->get('agenda/eventos',              'AtividadeController::eventos');
