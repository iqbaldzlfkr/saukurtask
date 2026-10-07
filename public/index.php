<?php

/**
 * Front Controller — All HTTP requests enter here.
 *
 * Responsibilities:
 * 1. Start the session.
 * 2. Load the Composer autoloader.
 * 3. Dispatch to the Router.
 *
 * Every other file in the app is only accessible through this entry point.
 */

declare(strict_types=1);

// Prevent direct access to files outside public/
define('APP_ROOT', dirname(__DIR__));

// Start session early
require_once APP_ROOT . '/app/Core/Session.php';
\App\Core\Session::start();

// Load Composer autoloader
require_once APP_ROOT . '/vendor/autoload.php';

// Dispatch
$router = new \App\Core\Router();
$router->dispatch();
