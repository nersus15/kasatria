<?php

function parseUrl($segment = true)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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