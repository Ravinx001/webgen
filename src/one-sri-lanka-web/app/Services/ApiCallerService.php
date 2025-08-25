<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class ApiCallerService
{
    public function get($url, $params = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)->get($url, $params);
            $response->throw();
            return $response->json();
        } catch (RequestException $e) {
            return [
                'status' => 'error',
                'response_body' => optional($e->response)->json() ?? optional($e->response)->body(),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    public function post($url, $data = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)->post($url, $data);
            $response->throw();
            return $response->json();
        } catch (RequestException $e) {
            return [
                'status' => 'error',
                'response_body' => optional($e->response)->json() ?? optional($e->response)->body(),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    public function put($url, $data = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)->put($url, $data);
            $response->throw();
            return $response->json();
        } catch (RequestException $e) {
            return [
                'status' => 'error',
                'response_body' => optional($e->response)->json() ?? optional($e->response)->body(),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }

    public function delete($url, $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)->delete($url);
            $response->throw();
            return $response->json();
        } catch (RequestException $e) {
            return [
                'status' => 'error',
                'response_body' => optional($e->response)->json() ?? optional($e->response)->body(),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
    }
}
