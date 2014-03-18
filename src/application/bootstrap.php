<?php

    if ( !file_exists('../vendor/autoload.php')) {
        echo 'Please complete "composer install" to run the application.';
        exit;
    }


    require '../vendor/autoload.php';