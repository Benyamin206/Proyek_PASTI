<?php

namespace App\Http\Controllers;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class ShipOwnerController extends Controller
{
    //

    public function showAllKapal()
    {
        try {
            $client = new Client(['base_uri' => 'http://localhost:9010']);
            
            $response = $client->request('GET', '/get-all-kapal');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $kapals = json_decode($body, true);
    
                return view('kapal.index', compact('kapals'));
            } else {
                $error = "Data tidak ditemukan";
                return view('kapal.index', compact('error'));
            }
        } catch (\Exception $e) {
            $error = "Server Kapal sedang bermasalah atau tidak aktif";
            return view('kapal.index', compact('error'));
        }
    }

    public function tambah_kapal(){
        return view('kapal.tambah');
    }

    public function store_kapal(Request $request)
    {
        // Validasi data input dari form
        // $request->validate([
        //     'nama' => 'required|string',
        //     'deskripsi' => 'required|string',
        //     'pemilik_kapal_id' => 'required|integer',
        // ]);

        // Ambil data dari form
        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'pemilik_kapal_id' => $request->pemilik_kapal_id,
        ];

        // Kirim data ke service tambah kapal menggunakan GuzzleHttp\Client
        $client = new Client(['base_uri' => 'http://localhost:9010']);
        
        try {
            $response = $client->request('POST', '/create-kapal', [
                'json' => $data,
            ]);
            
            // Ambil respons dari service tambah kapal
            $responseData = json_decode($response->getBody(), true);
            
            // Lakukan sesuatu dengan respons, misalnya redirect atau tampilkan pesan sukses
            return redirect()->back()->with('success', 'Kapal berhasil ditambahkan dengan ID: ' . $responseData['id']);
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->with('error', 'Server Kapal sedang bermasalah ');
        }
    }

    
    
}
