<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ApiCallerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $apiService;

    public function __construct(ApiCallerService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            // Fetch alerts from the API
            $response = $this->apiService->get(env('ONE_SRI_LANKA_API_URL') . '/alerts');

            $alerts = collect();
            if ($response && isset($response['success']) && $response['success']) {
                // Get only active alerts and group by category
                $allAlerts = collect($response['data'] ?? []);
                $alerts = $allAlerts->where('status', 'active')->groupBy('category');
            }

            return view('user.dashboard', compact('alerts'));
        } catch (\Exception $e) {
            Log::error('Error fetching alerts for dashboard: ' . $e->getMessage());

            // Return view with empty alerts collection if API fails
            return view('user.dashboard', ['alerts' => collect()]);
        }
    }
}
