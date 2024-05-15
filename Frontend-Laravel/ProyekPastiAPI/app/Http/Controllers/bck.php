<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

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

    public function updateStok($id, $qty)
    {
        $client = new Client(['base_uri' => 'http://localhost:9012']);

        try {
            $response = $client->request('PUT', '/update-stok', [
                'json' => [
                    'id' => $id,
                    'qty' => $qty
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            if ($response->getStatusCode() != 200) {
                throw new \Exception('Failed to update stok');
            }

            return $responseData;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = $response->getBody();
                $data = json_decode($body, true);

                return ['error' => $data['error'] ?? 'An error occurred'];
            }

            return ['error' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    public function store_order(Request $request)
    {
        $jadwal = $this->getJadwalById($request->jadwal_id);

        if (isset($jadwal['error'])) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
        }

        if ($request->qty > $jadwal['stok']) {
            return redirect()->back()->with('error', 'Quantity requested exceeds available stock');
        }

        $data = [
            'user_id' => $request->user_id,
            'jadwal_id' => $request->jadwal_id,
            'qty' => $request->qty,
            'total_amount' => $request->qty * $jadwal['harga'],
            'status_pembayaran' => 'Paid'
        ];


        $newStok = $jadwal['stok'] - $request->qty;

        $client = new Client(['base_uri' => 'http://localhost:9012']);

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


}
