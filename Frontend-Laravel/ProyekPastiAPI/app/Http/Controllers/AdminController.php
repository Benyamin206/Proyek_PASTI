<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\RequestException;

class AdminController extends Controller
{
    //
    public function showRegistrationForm()
    {
        return view('register_pemilik_kapal.form');
    }


    public function register_pemilik_kapal(Request $request)
{
    // Validasi input
    // $validatedData = $request->validate([
    //     'username' => 'required|unique:users|max:255', // Pastikan username unik dalam tabel users
    //     'password' => 'required|min:6', // Pastikan password memiliki minimal 6 karakter
    //     'email' => 'required|email|unique:users', // Pastikan email valid dan unik dalam tabel users
    // ]);

    // Ambil data pengguna dari input
    $userData = [
        'username' => $request->input('username'),
        'password' => $request->input('password'), // Gunakan nilai password dari input
        'email' => $request->input('email'), // Gunakan nilai email dari input
        'role' => 'ship_owner', // Set nilai role menjadi 'passenger'
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
            return Redirect::route('register_pemilik_kapal.form')->with('error', 'Username dan email sudah terpakai.');
        } else if($data['status'] == "berhasil"){
            return Redirect::route('home')->with('berhasil', 'Akun pemilik kapal berhasil didaftar');
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

    public function cni(){
        var_dump($this->getNahkodaById(1));
    }


    public function showAllNahkoda(){
        try {
            $client = new Client(['base_uri' => 'http://localhost:9008']);
            
            $response = $client->request('GET', '/get-all-nahkoda');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $nahkodas = json_decode($body, true);
    
                return view('nahkoda.index', compact('nahkodas'));
            } else {
                $error = "Data tidak ditemukan";
                return view('nahkoda.index', compact('error'));
            }
        } catch (\Exception $e) {
            $error = "Server Nahkoda sedang bermasalah atau tidak aktif";
            return view('nahkoda.index', compact('error'));
        }
    }

    public function tambah_nahkoda(){
        return view('nahkoda.tambah');
    }

    public function store_nahkoda(Request $request)
    {
        // Ambil data dari form
        $data = [
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        // Kirim data ke service tambah kapal menggunakan GuzzleHttp\Client
        $client = new Client(['base_uri' => 'http://localhost:9008']);
        
        try {
            $response = $client->request('POST', '/create-nahkoda', [
                'json' => $data,
            ]);
            
            // Ambil respons dari service tambah kapal
            $responseData = json_decode($response->getBody(), true);
            
            // Lakukan sesuatu dengan respons, misalnya redirect atau tampilkan pesan sukses
            return redirect()->back()->with('success', 'Nahkoda berhasil ditambahkan dengan ID: ' . $responseData['id']);
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->with('error', 'Server Nahkoda sedang bermasalah ');
        }
    }

    public function getNahkodaById($id){
        $client = new Client();
        $url = 'http://localhost:9008/get-nahkoda-by-id';
    
        // Ambil ID dari parameter fungsi
        if (!$id) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameter ID is required'
            ], 400);
        }
    
        try {
            $response = $client->request('GET', $url, [
                'query' => ['id' => $id]
            ]);
    
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
    
            return response()->json([
                'status' => $statusCode,
                'data' => $data
            ]);
    
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                $error = json_decode($body, true);
    
                return response()->json([
                    'status' => $statusCode,
                    'message' => 'Failed to get Nahkoda',
                    'error' => $error
                ]);
            }
    
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }


    

    public function editNahkoda(){
        
    }

    public function updateNahkoda(Request $request)
    {
        $client = new Client();
        $url = 'http://localhost:9008/update-nahkoda';

        // Ambil data dari request
        $data = [
            'id' => $request->input('id'),
            'nama' => $request->input('nama'),
            'nomor_hp' => $request->input('nomor_hp'),
            'jenis_kelamin' => $request->input('jenis_kelamin')
        ];

        try {
            $response = $client->request('PUT', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // return view('nahkoda.index')->with('success.edit', 'Berhasil Update');
            return Redirect::route('nahkoda.index')->with('success.edit', 'Berhasil Update');
        } catch (\Exception){
            return redirect()->back()->with('failed.edit', 'Server nahkoda bermasalah');
        }
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                // return response()->json([
                //     'status' => $statusCode,
                //     'message' => 'Failed to update Nahkoda',
                //     'error' => json_decode($body)
                // ]);
                return redirect()->back()->with('failed.edit', 'Server nahkoda bermasalah');
            }

            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Internal server error',
            //     'error' => $e->getMessage()
            // ]);
            return redirect()->back()->with('failed.edit', 'Server nahkoda bermasalah');
        }
    }

    public function updateRute(Request $request)
    {
        $client = new Client();
        $url = 'http://localhost:9006/update-rute';

        // Ambil data dari request
        $data = [
            'id' => $request->input('id'),
            'lokasi_berangkat' => $request->input('lokasi_berangkat'),
            'lokasi_tujuan' => $request->input('lokasi_tujuan'),
        ];

        try {
            $response = $client->request('PUT', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // return view('nahkoda.index')->with('success.edit', 'Berhasil Update');
            return Redirect::route('rute.index')->with('success.edit', 'Berhasil Update');
        } catch (\Exception){
            return redirect()->back()->with('failed.edit', 'Server rute bermasalah');
        }
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                // return response()->json([
                //     'status' => $statusCode,
                //     'message' => 'Failed to update Nahkoda',
                //     'error' => json_decode($body)
                // ]);
                return redirect()->back()->with('failed.edit', 'Server rute bermasalah');
            }

            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Internal server error',
            //     'error' => $e->getMessage()
            // ]);
            return redirect()->back()->with('failed.edit', 'Server rute bermasalah');
        }
    }

    public function updateKapal(Request $request)
    {
        $client = new Client();
        $url = 'http://localhost:9010/update-kapal';

        // Ambil data dari request
        $data = [
            'id' => $request->input('id'),
            'nama' => $request->input('nama'),
            'deskripsi' => $request->input('deskripsi'),
        ];

        try {
            $response = $client->request('PUT', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $data
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            // return view('nahkoda.index')->with('success.edit', 'Berhasil Update');
            return Redirect::route('kapal.index')->with('success.edit', 'Berhasil Update');
        } catch (\Exception){
            return redirect()->back()->with('failed.edit', 'Server rute bermasalah');
        }
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                // return response()->json([
                //     'status' => $statusCode,
                //     'message' => 'Failed to update Nahkoda',
                //     'error' => json_decode($body)
                // ]);
                return redirect()->back()->with('failed.edit', 'Server kapal bermasalah');
            }

            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Internal server error',
            //     'error' => $e->getMessage()
            // ]);
            return redirect()->back()->with('failed.edit', 'Server kapal bermasalah');
        }
    }

    public function edit_nahkoda($id){
        $client = new Client();
        $url = 'http://localhost:9008/get-nahkoda-by-id';
    
        // Ambil ID dari parameter fungsi
        if (!$id) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameter ID is required'
            ], 400);
        }
    
        try {
            $response = $client->request('GET', $url, [
                'query' => ['id' => $id]
            ]);
    
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
    
            return view('nahkoda.edit', compact('data'));
    
        } catch (\Exception $e){
            return view('nahkoda.index')->with('error', 'Server Nahkoda Sedang Bermasalah');
        } 
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                $errors = json_decode($body, true);
    
                return view('nahkoda.index')->with('error', 'errors');
            }
            return view('nahkoda.index')->with('error', 'server sedang bermasalah');
        }
    }

    public function edit_rute($id){
        $client = new Client();
        $url = 'http://localhost:9006/get-rute-by-id';
    
        // Ambil ID dari parameter fungsi
        if (!$id) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameter ID is required'
            ], 400);
        }
    
        try {
            $response = $client->request('GET', $url, [
                'query' => ['id' => $id]
            ]);
    
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
    
            return view('rute.edit', compact('data'));
    
        } catch (\Exception $e){
            return view('rute.index')->with('error', 'Server rute Sedang Bermasalah');
        } 
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                $errors = json_decode($body, true);
    
                return view('rute.index')->with('error', 'errors');
            }
            return view('rute.index')->with('error', 'server rute sedang bermasalah');
        }
    }

    public function edit_kapal($id){
        $client = new Client();
        $url = 'http://localhost:9010/get-kapal-by-id';
    
        // Ambil ID dari parameter fungsi
        if (!$id) {
            return response()->json([
                'status' => 400,
                'message' => 'Parameter ID is required'
            ], 400);
        }

        try {
            $response = $client->request('GET', $url, [
                'query' => ['id' => $id]
            ]);
    
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
    
            return view('kapal.edit', compact('data'));
    
        } catch (\Exception $e){
            return view('kapal.index')->with('error', 'Server kapal Sedang Bermasalah');
        } 
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                $errors = json_decode($body, true);
    
                return view('kapal.index')->with('error', 'errors');
            }
            return view('kapal.index')->with('error', 'server sedang bermasalah');
        }
    }

    public function showAllRute(){
        try {
            $client = new Client(['base_uri' => 'http://localhost:9006']);
            
            $response = $client->request('GET', '/get-all-rute');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $rutes = json_decode($body, true);
                return view('rute.index', compact('rutes'));
            } else {
                $error = "Data tidak ditemukan";
                return view('rute.index', compact('error'));
            }
        } catch (\Exception $e) {
            $error = "Server rute sedang bermasalah atau tidak aktif";
            return view('rute.index', compact('error'));
        }
    }



    public function tambah_rute(){
        return view('rute.tambah');
    }

    public function store_rute(Request $request)
    {
        // Ambil data dari form
        $data = [
            'lokasi_berangkat' => $request->lokasi_berangkat,
            'lokasi_tujuan' => $request->lokasi_tujuan,
        ];

        // Kirim data ke service tambah kapal menggunakan GuzzleHttp\Client
        $client = new Client(['base_uri' => 'http://localhost:9006']);
        
        try {
            $response = $client->request('POST', '/create-rute', [
                'json' => $data,
            ]);
            
            // Ambil respons dari service tambah kapal
            $responseData = json_decode($response->getBody(), true);
            
            // Lakukan sesuatu dengan respons, misalnya redirect atau tampilkan pesan sukses
            return redirect()->back()->with('success', 'Rute berhasil ditambahkan');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->with('error', 'Server Rute sedang bermasalah atau tidak aktif');
        }
    }

    public function showAllJadwal(){    
        try {
            $jadwals = [];
            
            // Mengambil data jadwal dari server yang berjalan di port 9012
            $jadwalClient = new Client(['base_uri' => 'http://localhost:9012']);
            $responseJadwal = $jadwalClient->request('GET', '/get-all-jadwal');
            $statusCodeJadwal = $responseJadwal->getStatusCode();
            
            if ($statusCodeJadwal === 200) {
                $bodyJadwal = $responseJadwal->getBody();
                $jadwals = json_decode($bodyJadwal, true);
            } else {
                $error = "Data jadwal tidak ditemukan";
                return view('jadwal.index', compact('error'));
            }
            
            // Mengambil data kapal dari server yang berjalan di port 9010
            $kapalClient = new Client(['base_uri' => 'http://localhost:9010']);
            foreach ($jadwals as &$jadwal) {
                $kapal_id = $jadwal['kapal_id'];
                try {
                    $responseKapal = $kapalClient->request('GET', '/get-kapal-by-id?id=' . $kapal_id);
                    $statusCodeKapal = $responseKapal->getStatusCode();
                    if ($statusCodeKapal === 200) {
                        $bodyKapal = $responseKapal->getBody();
                        $kapalData = json_decode($bodyKapal, true);
                        $jadwal['kapal_id'] = $kapalData['nama'];
                    }
                } catch (\Exception $e) {
                    $jadwal['kapal_id'] = "Service Kapal Bermasalah";
                }
            }
            
            // Mengambil data nahkoda dari server yang berjalan di port 9008
            $nahkodaClient = new Client(['base_uri' => 'http://localhost:9008']);
            foreach ($jadwals as &$jadwal) {
                $nahkoda_id = $jadwal['nahkoda_id'];
                try {
                    $responseNahkoda = $nahkodaClient->request('GET', '/get-nahkoda-by-id?id=' . $nahkoda_id);
                    $statusCodeNahkoda = $responseNahkoda->getStatusCode();
                    if ($statusCodeNahkoda === 200) {
                        $bodyNahkoda = $responseNahkoda->getBody();
                        $nahkodaData = json_decode($bodyNahkoda, true);
                        $jadwal['nahkoda_id'] = $nahkodaData['nama'];
                    }
                } catch (\Exception $e) {
                    $jadwal['nahkoda_id'] = "Service Nahkoda Bermasalah";
                }
            }

            // Mengambil data rute dari server yang berjalan di port 9006
            $ruteClient = new Client(['base_uri' => 'http://localhost:9006']);
            foreach ($jadwals as &$jadwal) {
                $rute_id = $jadwal['rute_id'];
                try {
                    $responseRute = $ruteClient->request('GET', '/get-rute-by-id?id=' . $rute_id);
                    $statusCodeRute = $responseRute->getStatusCode();
                    if ($statusCodeRute === 200) {
                        $bodyRute = $responseRute->getBody();
                        $ruteData = json_decode($bodyRute, true);
                        $jadwal['rute_id'] = $ruteData['lokasi_berangkat']. " - " . $ruteData['lokasi_tujuan'];
                    }
                } catch (\Exception $e) {
                    $jadwal['rute_id'] = "Service Rute Bermasalah";
                }
            }

            return view('jadwal.index', compact('jadwals'));
            
        } catch (\Exception $e) {
            $error = "Server jadwal sedang bermasalah atau tidak aktif";
            return view('jadwal.index', compact('error'));
        }
    }

    public function tambah_jadwal(){
        // Kapal
        $kapals = [];
        $kapalError = null;
        try {
            $client = new Client(['base_uri' => 'http://localhost:9010']);
            
            $response = $client->request('GET', '/get-all-kapal');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $kapals = json_decode($body, true);
            } else {
                $kapalError = "Service Kapal sedang bermasalah atau tidak aktif";
            }
        } catch (\Exception $e) {
            $kapalError = "Service Kapal sedang bermasalah atau tidak aktif";
        }
    
        // Nahkoda
        $nahkodas = [];
        $nahkodaError = null;
        try {
            $client = new Client(['base_uri' => 'http://localhost:9008']);
            
            $response = $client->request('GET', '/get-all-nahkoda');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $nahkodas = json_decode($body, true);
            } else {
                $nahkodaError = "Service Nahkoda sedang bermasalah atau tidak aktif";
            }
        } catch (\Exception $e) {
            $nahkodaError = "Service Nahkoda sedang bermasalah atau tidak aktif";
        }
    
        // Rute
        $rutes = [];
        $ruteError = null;
        try {
            $client = new Client(['base_uri' => 'http://localhost:9006']);
            
            $response = $client->request('GET', '/get-all-rute');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $rutes = json_decode($body, true);
            } else {
                $ruteError = "Service Rute sedang bermasalah atau tidak aktif";
            }
        } catch (\Exception $e) {
            $ruteError = "Service Rute sedang bermasalah atau tidak aktif";
        }
    
        return view('jadwal.tambah', compact('kapals', 'nahkodas', 'rutes', 'kapalError', 'nahkodaError', 'ruteError'));
    }
    
    public function store_jadwal(Request $request){
        // Ambil data dari form
        $data = [
            'kapal_id' => $request->kapal_id,
            'nahkoda_id' => $request->nahkoda_id,
            'rute_id' => $request->rute_id,
            'waktu_berangkat' => $request->waktu_berangkat,
            'stok' => $request->stok,
            'harga' => $request->harga
        ];

        // Kirim data ke service tambah kapal menggunakan GuzzleHttp\Client
        $client = new Client(['base_uri' => 'http://localhost:9012']);
        
        try {
            $response = $client->request('POST', '/create-jadwal', [
                'json' => $data,
            ]);
            
            // Ambil respons dari service tambah kapal
            $responseData = json_decode($response->getBody(), true);
            
            // Lakukan sesuatu dengan respons, misalnya redirect atau tampilkan pesan sukses
            return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->back()->with('error', 'Server Jadwal sedang bermasalah atau tidak aktif');
        } 
    }

    public function updateStatusPembayaran(Request $request)
    {
        $orderID = $request->input('order_id');
        $statusPembayaran = $request->input('status_pembayaran');

        if (!$orderID || !$statusPembayaran) {
            return response()->json(['error' => 'Order ID and Status Pembayaran are required'], 400);
        }

        $client = new Client();

        try {
            $response = $client->request('PUT', 'http://localhost:9014/update-status-pembayaran', [
                'json' => [
                    'order_id' => $orderID,
                    'status_pembayaran' => $statusPembayaran
                ]
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);

            return response()->json($data, $response->getStatusCode());

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
    
    
    

}
