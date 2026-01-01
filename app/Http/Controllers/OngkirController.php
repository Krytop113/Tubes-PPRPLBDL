<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OngkirController extends Controller
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL');
    }

    public function checkCost(Request $request)
    {
        $origin = 23;
        $destination = (int) $request->destination;
        $courier = strtolower(trim($request->courier));

        $response = Http::withoutVerifying()->withHeaders([
            'key' => $this->apiKey
        ])->asForm()->post($this->baseUrl . 'cost', [
            'origin'        => $origin,
            'destination'   => $destination,
            'weight'        => 500,
            'courier'       => $courier,
        ]);

        $data = $response->json();

        Log::info("Response RajaOngkir:", $data);

        if (isset($data['rajaongkir']['status']) && $data['rajaongkir']['status']['code'] != 200) {
            return response()->json([
                'success' => false,
                'message' => "API Error: " . $data['rajaongkir']['status']['description']
            ], 400);
        }

        $results = $data['rajaongkir']['results'][0]['costs'] ?? [];

        if (count($results) > 0) {
            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "RajaOngkir: Tidak ada ongkir dari ID 22 ke ID $destination via $courier. Pastikan ID Kota benar."
        ], 404);
    }
}
