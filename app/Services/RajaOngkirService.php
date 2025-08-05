<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
        $this->baseUrl = config('services.rajaongkir.base_url');
    }

    public function getProvinces()
    {
        if (!$this->apiKey) {
            throw new \Exception('RajaOngkir API Key not configured');
        }

        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/destination/province');

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch provinces');
        } catch (\Exception $e) {
            throw new \Exception('RajaOngkir API error: ' . $e->getMessage());
        }
    }

    public function getCities($provinceId = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('RajaOngkir API Key not configured');
        }

        try {
            $url = $this->baseUrl . '/destination/city';
            if ($provinceId) {
                $url .= '/' . $provinceId;
            }
            
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'key' => $this->apiKey
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch cities');
        } catch (\Exception $e) {
            throw new \Exception('RajaOngkir API error: ' . $e->getMessage());
        }
    }

    public function getDistricts($cityId = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('RajaOngkir API Key not configured');
        }

        try {
            $url = $this->baseUrl . '/destination/district';
            if ($cityId) {
                $url .= '?city_id=' . $cityId;
            }
            
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'key' => $this->apiKey
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch districts');
        } catch (\Exception $e) {
            throw new \Exception('RajaOngkir API error: ' . $e->getMessage());
        }
    }

    public function getShippingCost($originCityId, $destinationCityId, $weight, $courier)
    {
        if (!$this->apiKey) {
            throw new \Exception('RajaOngkir API Key not configured');
        }

        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($this->baseUrl . '/calculate/district/domestic-cost', [
                'origin' => $originCityId,
                'destination' => $destinationCityId,
                'weight' => $weight,
                'courier' => $courier,
                'price' => 'lowest'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to calculate shipping cost: ' . $response->body());
        } catch (\Exception $e) {
            throw new \Exception('RajaOngkir API error: ' . $e->getMessage());
        }
    }

    public function calculateShippingCosts($destinationCityId, $weight)
    {
        $originCityId = Setting::get('shop_city_id');
        
        if (!$originCityId) {
            throw new \Exception('Shop city not configured');
        }

        $couriers = ['jne', 'pos', 'tiki'];
        $results = [];

        foreach ($couriers as $courier) {
            try {
                $response = $this->getShippingCost($originCityId, $destinationCityId, $weight, $courier);
                
                // Handle the new API response structure
                if (isset($response['data']) && is_array($response['data'])) {
                    $results[$courier] = $response['data'];
                }
            } catch (\Exception $e) {
                // Log error but continue with other couriers
                Log::error("Error getting shipping cost for {$courier}: " . $e->getMessage());
            }
        }

        return $results;
    }

    public function isSameCity($customerCityId)
    {
        $shopCityId = Setting::get('shop_city_id');
        return $shopCityId && $customerCityId && $shopCityId == $customerCityId;
    }
}
