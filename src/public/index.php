<?php


    if ( !file_exists(__DIR__  . '/../../vendor/autoload.php')) {
        echo 'Please complete "composer install" to run the application.';
        exit;
    }


    require __DIR__ . '/../../vendor/autoload.php';
    require __DIR__ . '/../application/bootstrap.php';