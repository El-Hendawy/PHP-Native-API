<?php

//Disable Error Displaying as it's in production phase

ini_set('display_errors', 0);

//Writing the auto loader class depending on the class name which will include the namespace

spl_autoload_register(function ($class_name) {

    include $class_name . '.php';
    
});

?>