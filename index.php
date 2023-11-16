<?php
ini_set('include_path', 'C:\xampp\htdocs\kasatria\php_google_oauth_login\google-api-php-client\src');
require_once './config.php';
// START SESSION
session_start();

// SET BASEURL
if (!defined('BASEURL')) {
    $host = $_SERVER['HTTP_HOST'];
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $name = '';
    /* if ($host == 'localhost') */ $name = 'kasatria';

    define('BASEURL', "$protocol://$host" . (!empty($name) ? ("/$name/") : '/'));
}

include_once './functions/utils.php';

// Get Controller and Route from Url
$urls = parseUrl();

// Run Route
if(!empty($urls)){
    // Cek Controller File
    $controllers = parseUrl();
    $class = ucfirst($controllers[0]);
    $method = count($controllers) >= 2 ? $controllers[1] : 'index';
    if(count($controllers) > 2) {
        unset($controllers[0], $controllers[1]);
        $params = (array) $controllers;
    }else{
        $params = [];
    }
    if(!file_exists("./controllers/$class.php")){
        echo "Controller file <b><i>controllers/$class.php</i></b> not found";
        exit;
    }
    include_once "./controllers/$class.php";
    $clz = new $class();

    if(!method_exists($clz, $method)){
        echo "Method <b><i> $method </b></i>not found in <b><i>controllers/$class.php</i></b>";
        exit;
    }
    call_user_func_array(array($clz, $method), $params);
    exit;
}else{
    if(empty(sessiondata('login'))) redirect(base_url('auth/login'));
    // Home Page
    if(defined('HOME_CONTROLLER')){
        $class = explode('@', HOME_CONTROLLER)[0];
        $method = explode('@', HOME_CONTROLLER)[1] ?? 'index';
        include_once "./controllers/$class.php";
        
        $clz = new $class();
        call_user_func_array(array($clz, $method), []);
        exit;
    }
    view('homepage');
}