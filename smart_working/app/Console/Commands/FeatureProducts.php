<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FeatureProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:featured-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $cacheKey = 'magentoapi_token';


    /**
     * Get the cached token or generate a new one if expired.
     */
    public function getToken()
    {
        // Check if token exists in cache
        $token = Cache::get($this->cacheKey);
        if (!$token) {
            // Fetch a new token from API
            $token = $this->fetchNewToken();
            // Store in cache with expiration (e.g., 1 hour)
            Cache::put($this->cacheKey, $token, now()->addMinutes(10));
        }
		return $token;
    }

    /**
     * Fetch a new token from the external API.
     */
    private function fetchNewToken()
    {
        $username = config('mageconfig.username');
        $password = config('mageconfig.password');

        $response = Http::post(config('mageconfig.apiurl') . 'integration/admin/token', [
            'username' => $username,
            'password' => $password,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to fetch token from API');
        }

        return $response->json(); // Adjust according to API response
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prreq = Http::withToken($this->getToken())->get(config('mageconfig.apiurl') . 'products?searchCriteria[pageSize]=10');
        $prres = $prreq->json();
       
        $results = (!empty($prres['items'])) ? $prres['items'] : [];
        foreach ($results as $row) {
            $productdata = [
                'name' => $row['name'],
                'sku' => $row['sku'],
                'price' => $row['price'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            Product::create($productdata);
        }
    }
}
