<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Redirect;

class PassengerController extends Controller
{
    //
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

            return view('tiket_jadwal.index', compact('jadwals'));
            
        } catch (\Exception $e) {
            $error = "Server jadwal sedang bermasalah atau tidak aktif";
            return view('tiket_jadwal.index', compact('error'));
            // return view('tiket_jadwal.index')->with('error', $error);
            // return Redirect::route('tiket_jadwal.index')->with('error', $error);
        }
    }

    public function getAllOrder(){
        try {
            $client = new Client(['base_uri' => 'http://localhost:9014']);
            
            $response = $client->request('GET', '/get-all-order');
            
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                $body = $response->getBody();
                $orders = json_decode($body, true);
    
                return $orders;
            } else {
                $error = "Data tidak ditemukan";
                $orders = [];
                return $orders;
            }
        } catch (\Exception $e) {
            $error = "Server Nahkoda sedang bermasalah atau tidak aktif";
            $orders = [];
            $orders[] = "error";
            return $orders;
        }
    }

    public function getJadwalById($id)
    {
        $client = new Client();
        $url = 'http://127.0.0.1:9012/get-jadwal-by-id';
        
        try {
            // Sending a GET request to the specified URL with the 'id' parameter
            $response = $client->request('GET', $url, [
                'query' => ['id' => $id]
            ]);
            
            // Getting the response body and decoding it to an associative array
            $data = json_decode($response->getBody(), true);
            
            // Check if data is null or empty
            if (is_null($data)) {
                return response()->json(['error' => 'Data not found'], 404);
            }

            // Returning the data as an array
            return $data;
        } catch (\Exception $e) {
            // Handling errors and returning a response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cji(){
        $data =  $this->getJadwalById(1);
        dd($data);
    }

    public function cus(){
        dd($this->updateStok(1, 35));
    }

    public function cco(){
        dd($this->createOrder());
    }

    public function cgou(){
        dd($this->getOrderByUserID(3));
    }

    public function updateStok($id, $qty)
    {
        $client = new Client(['base_uri' => 'http://localhost:9012']);
    
        try {
            // Panggil endpoint update-stok dengan method PUT
            $response = $client->request('PUT', '/update-stok', [
                'json' => [
                    'id' => strval($id), // Pastikan id adalah string
                    'qty' => strval($qty) // Pastikan qty adalah string
                ]
            ]);
    
            // Periksa kode respons
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                // Jika sukses, dapatkan pesan respons
                $data = json_decode($response->getBody()->getContents(), true);
                return ['message' => $data['message']];
            } else {
                return ['error' => "Unexpected status code $statusCode"];
            }
        } catch (RequestException $e) {
            // Tangani kesalahan permintaan
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody();
                $data = json_decode($body, true);
                return ['error' => $data['error'] ?? 'An error occurred'];
            } else {
                return ['error' => 'Server error: ' . $e->getMessage()];
            }
        } catch (\Exception $e) {
            // Tangani kesalahan lainnya
            return ['error' => $e->getMessage()];
        }
    }

    public function createOrder()
    {
        $data = [
            'user_id' => "12",
            'jadwal_id' => "2",
            'qty' => "3",
            'total_amount' => "60000",
            'status_pembayaran' => 'Paid' // Set status_pembayaran sesuai kebutuhan Anda
        ];

        try {
            $client = new Client(['base_uri' => 'http://localhost:9014']);
            $response = $client->request('POST', '/create-order', [
                'json' => $data,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['id'])) {
                // Jika berhasil, return pesan sukses
                return response()->json(['message' => 'Order created successfully'], 200);
            } else {
                // Jika terjadi kesalahan dalam pembuatan order
                return response()->json(['message' => 'Failed to create order'], 500);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi kesalahan dalam panggilan HTTP atau server tidak aktif
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function store_order(Request $request)
    {
        $jadwal = $this->getJadwalById($request->jadwal_id);

        if (isset($jadwal['error'])) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
        }

        if ($request->qty > $jadwal['stok']) {
            return redirect()->back()->with('error', 'jumlah tiket tidak boleh melebihi stok');
        }

        $data = [
            'user_id' => strval($request->user_id),
            'jadwal_id' => strval($request->jadwal_id),
            'qty' => strval($request->qty),
            'total_amount' => strval($request->qty * $jadwal['harga']),
            'status_pembayaran' => 'Paid'
        ];


        $newStok = $jadwal['stok'] - $request->qty;

        $client = new Client(['base_uri' => 'http://localhost:9014']);

        try {
            $response = $client->request('POST', '/create-order', [
                'json' => $data,
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['error'])) {
                throw new \Exception($responseData['error']);
            }

            $updateResponse = $this->updateStok($request->jadwal_id, $newStok);

            if (isset($updateResponse['error'])) {
                throw new \Exception($updateResponse['error']);
            }

            return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan dan stok berhasil diperbarui');
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody();
                $data = json_decode($body, true);
                return redirect()->back()->with('error', $data['error'] ?? 'An error occurred');
            }

            return redirect()->back()->with('error', 'Server Jadwal sedang bermasalah atau tidak aktif: ' . $e->getMessage());
        }
    }


    public function getOrderByUserID($userID)
    {
        try {
            // Buat instance Guzzle client
            $client = new Client(['base_uri' => 'http://localhost:9014']);

            // Panggil endpoint get-order-by-user-id dengan method GET
            $response = $client->request('GET', '/get-order-by-id-user', [
                'query' => ['user_id' => $userID],
            ]);

            // Ubah respons menjadi array
            $orders = json_decode($response->getBody(), true);

            // Kembalikan data array jika berhasil
            return view('tiket_jadwal.myOrder', compact('orders'));
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Tangani kesalahan jika terjadi kesalahan dalam panggilan HTTP
            // $errorMessage = $e->getMessage();
            // throw new \Exception($errorMessage);
            return view('tiket_jadwal.myOrder')->with('error', 'Terjadi kesalahan di server');
        } catch (\Exception $e) {
            // Tangani kesalahan lainnya
            // $errorMessage = $e->getMessage();
            // throw new \Exception($errorMessage);
            return view('tiket_jadwal.myOrder')->with('error', 'Terjadi kesalahan di server');

        }
    }
    
    
    
    public function myOrder($userId)
    {
        $data = $this->getOrderByUserID($userId);
    
        // Jika terdapat error dalam data yang dikembalikan
        if (isset($data['error'])) {
            return view('tiket_jadwal.myOrder', compact('data'));
        }
    
        return view('tiket_jadwal.myOrder', compact('data'));
    }
    
    
        


}
