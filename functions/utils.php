<?php

function parseUrl($segment = true)
{
    $tmp = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $batas = strpos($tmp, '?');
    $url = $tmp;
    if($batas !== false){
        $url = substr($tmp, 0, $batas);
        $query = substr($tmp, $batas + 1);
        $get = explode('&', $query);
        foreach($get as $v){
            $arr = explode('=', $v);
            $key = $arr[0];
            $value = null;
            if(count($arr) == 2)
                $value = $arr[1];

            $_GET[$key] = $value;
        }
    }
    $url = str_replace(BASEURL, '', $url);
    if ($segment)
        return empty($url) ? $url : explode('/', $url);
    else
        return $url;
}

function view($view, $data = [])
{
    extract($data);
    try {
        require_once  './views/' . $view . '.php';
    } catch (\Throwable $th) {
        echo json_encode(['message' => 'error', 'err' => print_r($th, true)]);
    }
}

function sessiondata($index = 'login', $kolom = null)
{
    $data = isset( $_SESSION[$index]) ?  $_SESSION[$index] : [];
    return empty($kolom) ? $data : (isset($data[$kolom]) ? $data[$kolom] : null);
}

function redirect($url)
{
    header('Location: ' . $url);
}
function base_url($path = null)
{
    return empty($path) ? BASEURL : BASEURL . $path;
}

function makeGoogleApiClient(){
    require_once './vendor/autoload.php';
    $client = new Google_Client();
    $client->setDeveloperKey(G_APIKEY);
    $client->setClientId(G_CLIENTID);
    $client->setClientSecret(G_SECRET);
    $client->setRedirectUri(base_url('auth/google'));
    $client->setScopes(array( \Google\Service\Sheets::SPREADSHEETS,'email', 'profile'));
    return $client;
}

function add_cachedJavascript($js, $type = 'file', $pos="body:end", $data = array())
    {    
        $s = '';    
        try {
            if($type == 'file'){
                ob_start();
                if (!empty($data))
                    extract($data);
                    
                
                include_once './assets/js/' .$js . '.js';
            }

            $s =  $type == 'file' ? ob_get_contents() : $js;
            if($type == 'file')
                ob_end_clean();
            return $s;
        } catch (\Throwable $th) {
           print_r($th);
        }
    }