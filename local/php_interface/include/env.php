<?php
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

define('IS_PRODUCTION', getenv('MODE') === 'production');
define('MANIFEST_PATH', $_SERVER['DOCUMENT_ROOT'] . getenv('VITE_BASE_PATH') . '.vite/manifest.json');