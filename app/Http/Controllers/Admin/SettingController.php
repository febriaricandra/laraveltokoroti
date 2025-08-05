<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'required|string',
            'shop_phone' => 'required|string|max:20',
            'shop_email' => 'required|email|max:255',
            'shop_postal_code' => 'required|string|max:10',
            'shop_province_id' => 'required|integer',
            'shop_city_id' => 'required|integer',
            'enable_shipping_cost' => 'boolean',
        ]);

        foreach ($validatedData as $key => $value) {
            if ($key === 'enable_shipping_cost') {
                Setting::set($key, $value, 'boolean', 'shipping', 'Enable/disable shipping cost calculation');
            } elseif (in_array($key, ['shop_province_id', 'shop_city_id'])) {
                Setting::set($key, $value, 'number', 'shipping', 'Shop location for shipping calculation');
            } else {
                Setting::set($key, $value, 'text', 'general', 'Shop information');
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function getProvinces()
    {
        $apiKey = config('services.rajaongkir.api_key');
        
        if (!$apiKey) {
            return response()->json(['error' => 'RajaOngkir API Key not configured in .env file'], 400);
        }

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'key' => $apiKey
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'API connection failed'], 500);
        }
    }

    public function getCities($provinceId)
    {
        $apiKey = config('services.rajaongkir.api_key');
        
        if (!$apiKey) {
            return response()->json(['error' => 'RajaOngkir API Key not configured in .env file'], 400);
        }

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'key' => $apiKey
            ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}");

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch cities'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'API connection failed'], 500);
        }
    }

    public function getShippingCost(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string|in:jne,pos,tiki'
        ]);

        $apiKey = Setting::get('rajaongkir_api_key');
        $originCityId = Setting::get('shop_city_id');
        
        if (!$apiKey || !$originCityId) {
            return response()->json(['error' => 'Shop settings not configured'], 400);
        }

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'key' => $apiKey
            ])->post('https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
                'origin' => $originCityId,
                'destination' => $request->destination_city_id,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to calculate shipping cost'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'API connection failed'], 500);
        }
    }
}
