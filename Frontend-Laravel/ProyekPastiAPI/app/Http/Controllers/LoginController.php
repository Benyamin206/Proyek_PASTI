<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('login.form');
    }
    
    public function login(Request $request)
    {
        $client = new Client(['base_uri' => 'http://localhost:9004']);
    
        try {
            $response = $client->request('GET', '/check-credentials', [
                'query' => [
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
    
            if ($data['status'] === 'success') {
                session(['user_id' => $data['id']]);
                session(['role' => $data['role']]);
                session(['username' => $data['username']]);
                // return redirect()->route('home');
                return view('home');
            } else {
                return redirect()->route('login_form')->with('error', 'email atau password salah.');
            }
        } catch (\Exception $e) {
            return redirect()->route('login_form')->with('error', 'Server sedang bermasalah atau tidak aktif');
        }
        
    }
    
    
}


