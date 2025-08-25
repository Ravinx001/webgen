<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Log;

class ApiTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan api:test GET https://api.example.com
     */
    protected $signature = 'api:test {method} {url} {--data=} {--token=}';

    /**
     * The console command description.
     */
    protected $description = 'Test an API call using ApiHelper';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $method = $this->argument('method');
        $url = $this->argument('url');
        $data = $this->option('data') ? json_decode($this->option('data'), true) : [];
        $token = $this->option('token');

        $headers = [
            'Accept' => 'application/json',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $this->info("Calling API: {$method} {$url}");
        
        $response = ApiHelper::callApi($method, $url, $data, $headers);

        $this->line(json_encode($response, JSON_PRETTY_PRINT));
        Log::info("API Response: " . json_encode($response, JSON_PRETTY_PRINT));
    }
}
