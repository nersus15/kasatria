<?php

use Google\Service\Sheets;

class Home{
    function index(){
        $client = makeGoogleApiClient();
        $client->setAccessToken(sessiondata('login', 'token'));
        $service = new Sheets($client);

        $response = $service->spreadsheets_values->get(GDRIVE_FILE_KEY, 'Sheet1');
        $values = $response->getValues();
       
        $data = [];
        $keys = $values[0];
        $i = 1;
        $j = 1;
        foreach($values as $k => $v){
            if($k == 0) continue;
            $tmp = [];
            $tmp['i'] = $i;
            
            $tmp['j'] = $j;
            
            $j ++;
            if($j == 21){
                $i += 1;
                $j = 1;
            }
            foreach($v as $key => $val){
                if($keys[$key] == 'Net Worth')
                    $val = intval(str_replace(['$', ','], '', $val));
                $tmp[$keys[$key]] = $val;
               
            }
            $data[] = $tmp;
        }
        view('homepage', $data);
    }
}