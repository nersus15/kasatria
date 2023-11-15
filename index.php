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
    if ($host == 'localhost') $name = 'kasatria';

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
    $batas = strpos($method, '?');
    if($batas !== false){
        $m = substr($method, 0, $batas);
        $get = explode('&', substr($method, $batas + 1));
        $method = $m;

        foreach($get as $v){
            $arr = explode('=', $v);
            $key = $arr[0];
            $value = null;
            if(count($arr) == 2)
                $value = $arr[1];

            $_GET[$key] = $value;
        }
    }
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
    view('homepage');
}