<?php

use Google\Service\Oauth2;
use Google\Service\Sheets;
use Google\Service\Sheets\Spreadsheet;

class Auth
{
    function login()
    {
        require_once './vendor/autoload.php';
        // create Client Request to access Google API 
        $client = makeGoogleApiClient();
        view('must_login', ['auth_url' => $client->createAuthUrl()]);
    }
    function google()
    {
        $client = makeGoogleApiClient();
        $token = $client->fetchAccessTokenWithAuthCode(urldecode($_GET['code']));
        // var_dump($token);die;
        $client->setAccessToken($token['access_token']);

        
        // get profile info 
        $google_oauth = new Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $data = array_merge(['token' =>  $token['access_token']] , (array) $google_account_info);
        $_SESSION['login'] = $data;

        redirect(base_url());
    }
    function myfile(){
        $client = makeGoogleApiClient();
        $client->setAccessToken(sessiondata('login', 'token'));
        $service = new Sheets($client);

        $response = $service->spreadsheets_values->get(GDRIVE_FILE_KEY, 'Sheet1');
        $values = $response->getValues();
        echo json_encode($values);die;
    }
    function logout()
    {
        $client = makeGoogleApiClient();
        $client->revokeToken();
        unset($_SESSION['login']);
        redirect(base_url('auth/login'));
    }
}
