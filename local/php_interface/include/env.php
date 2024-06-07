<?php
use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

define('IS_PRODUCTION', getenv('MODE') === 'production');