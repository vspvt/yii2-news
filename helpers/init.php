<?php
$dotenv = new Dotenv\Dotenv(__DIR__ . DIRECTORY_SEPARATOR . '..');
$dotenv->load();

defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG', false));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV', 'prod'));
