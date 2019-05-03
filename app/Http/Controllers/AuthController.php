<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

    	$http = new \GuzzleHttp\Client;
    	


		$response = $http->post(url('oauth/token'), [
		    'form_params' => [
		        'grant_type' => 'password',
		        'client_id' => 2,
		        'client_secret' => 'hPKd70GUcCtmsEVkRY1Aq7kNw6xiRtKzd4Cg8BXq',
		        'username' => $request->username,
		        'password' => $request->password
		        
		    ],
		]);

		  return  $response->getBody();
		
    }
}
