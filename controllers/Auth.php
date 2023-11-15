<?php

use Google\Service\Oauth2;
use Google\Service\Sheets;

class Auth
{
    function login()
    {
        require_once './vendor/autoload.php';
        // create Client Request to access Google API 
        $client = $this->makeClient();
        view('must_login', ['auth_url' => $client->createAuthUrl()]);
    }
    function google()
    {
        $client = $this->makeClient();
        $token = $client->fetchAccessTokenWithAuthCode(urldecode($_GET['code']));
        $client->setAccessToken($token['access_token']);

        
        // get profile info 
        $google_oauth = new Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $data = array_merge(['token' =>  $token['access_token']] , (array) $google_account_info);
        $_SESSION['login'] = $data;
        redirect(base_url());
    }
    function myfile(){
        $client = $this->makeClient();
        $service = new Sheets($client);
        $spredsheet = $service->spreadsheets->get(GDRIVE_FILE_KEY);
        var_dump($spredsheet);
    }
    function logout()
    {
        $client = $this->makeClient();
        $client->revokeToken();
        unset($_SESSION['login']);
        redirect(base_url('auth/login'));
    }

    private function makeClient(){
        require_once './vendor/autoload.php';
        $client = new Google_Client();
        $client->setClientId(G_CLIENTID);
        $client->setClientSecret(G_SECRET);
        $client->setRedirectUri(base_url('auth/google'));
        $client->setScopes(array( \Google\Service\Sheets::DRIVE,'email', 'profile'));
        return $client;
    }
}
