<?php

    define('APP_PATH',__DIR__);

    spl_autoload_register(function($class_name){
        $class_name  = str_replace('\\','/',$class_name);
        if(strstr($class_name,'Eli/Monitor')){
            $class_name = str_replace('Eli/Monitor','src',$class_name);
        }
        $class_path = APP_PATH.'/'.$class_name.'.php';
        if(file_exists($class_path)){
            require_once $class_path;
        }else{
            return false;
        }
    });

    $monitor = new Eli\Monitor\Monitor('udp');
    $monitor->record();