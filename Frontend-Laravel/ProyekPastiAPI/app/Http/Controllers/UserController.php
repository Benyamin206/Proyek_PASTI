<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //
    public function showRegistrationForm()
    {
        return view('register.form');
    }


    public function register(Request $request)
{
    // Ambil data pengguna dari input
    $userData = [
        'username' => $request->input('username'),
        'password' => $request->input('password'), // Gunakan nilai password dari input
        'email' => $request->input('email'), // Gunakan nilai email dari input
        'role' => 'passenger', // Set nilai role menjadi 'passenger'
        'jenis_kelamin' => $request->input('jenis_kelamin'),
        'nomor_telepon' => $request->input('nomor_telepon'),
        'alamat' => $request->input('alamat')
    ];

    // Buat instance Guzzle HTTP client
    $client = new Client(['base_uri' => 'http://localhost:9004']); // Sesuaikan dengan URL aplikasi Go Anda

    try {
        // Kirim permintaan POST ke endpoint /add-user dengan menggunakan Guzzle
        $response = $client->request('POST', '/add-user', [
            'json' => $userData, // Kirim data pengguna dalam format JSON
        ]);

        // Ambil respons dari server Go
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if($data['status'] == "gagal"){
            return Redirect::route('register.form')->with('error', 'Username dan email sudah terpakai.');
        } else if($data['status'] == "berhasil"){
            return Redirect::route('login_form')->with('berhasil', 'Akun berhasil didaftar, silahkan login');;
        }

        // return $data['status'];

        // Berhasil menambahkan pengguna
        // return response()->json($data, $response->getStatusCode());
        
        // $oke = response()->json($data, $response->getStatusCode());
        // return Redirect::route('login_form');
    } catch (\Exception $e) {
        // Tangani kesalahan jika ada
        // return response()->json(['error' => $e->getMessage()], 500);
        return Redirect::route('register.form')->with('error', 'Server sedang bermasalah atau tidak aktif.');
    }

}

public function logout(Request $request)
{
    Session::forget('user_id');
    Session::forget('role');
    Session::forget('username');
    return redirect()->route('login_form');
}



}
