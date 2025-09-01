<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiCallerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlertController extends Controller
{
    protected $apiService;

    public function __construct(ApiCallerService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of alerts
     */
    public function index()
    {
        try {
            $response = $this->apiService->get(env('ONE_SRI_LANKA_API_URL') . '/alerts');

            $alerts = collect();
            if ($response && isset($response['success']) && $response['success']) {
                $alerts = collect($response['data'] ?? []);
            }

            return view('admin.pages.complaints.alerts.index', [
                'title' => 'Alerts Management',
                'subtitle' => 'Manage system alerts and notifications',
                'alerts' => $alerts
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching alerts: ' . $e->getMessage());

            return view('admin.pages.complaints.alerts.index', [
                'title' => 'Alerts Management',
                'subtitle' => 'Manage system alerts and notifications',
                'alerts' => collect()
            ])->with('error', 'Unable to fetch alerts. Please try again later.');
        }
    }

    /**
     * Show the form for creating a new alert
     */
    public function create()
    {
        return view('admin.pages.complaints.alerts.create', [
            'title' => 'Create Alert',
            'subtitle' => 'Add a new system alert'
        ]);
    }

    /**
     * Store a newl created alert
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:security,maintenance,emergency,system,general',
            'priority' => 'required|in:low,medium,high,critical'
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'priority' => $request->priority,
                'status' => 'active'
            ];

            $response = $this->apiService->post(env('ONE_SRI_LANKA_API_URL') . '/alerts', $data);

            if ($response && isset($response['success']) && $response['success']) {
                return redirect()->route('admin.alerts.index')
                    ->with('success', 'Alert created successfully!');
            } else {
                $errorMessage = $response['message'] ?? 'Failed to create alert';
                return back()->withInput()
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error creating alert: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'An error occurred while creating the alert. Please try again.');
        }
    }

    /**
     * Display the specified alert
     */
    public function show($id)
    {
        try {
            $response = $this->apiService->get(env('ONE_SRI_LANKA_API_URL') . "/alerts/{$id}");

            if ($response && isset($response['success']) && $response['success']) {
                $alert = $response['data'];

                return view('admin.pages.complaints.alerts.show', [
                    'title' => 'Alert Details',
                    'subtitle' => 'View alert information',
                    'alert' => $alert
                ]);
            } else {
                return redirect()->route('admin.alerts.index')
                    ->with('error', 'Alert not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching alert: ' . $e->getMessage());

            return redirect()->route('admin.alerts.index')
                ->with('error', 'Unable to fetch alert details.');
        }
    }

    /**
     * Show the form for editing the specified alert
     */
    public function edit($id)
    {
        try {
            $response = $this->apiService->get(env('ONE_SRI_LANKA_API_URL') . "/alerts/{$id}");

            if ($response && isset($response['success']) && $response['success']) {
                $alert = $response['data'];

                return view('admin.pages.complaints.alerts.edit', [
                    'title' => 'Edit Alert',
                    'subtitle' => 'Modify alert details',
                    'alert' => $alert
                ]);
            } else {
                return redirect()->route('admin.alerts.index')
                    ->with('error', 'Alert not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching alert for edit: ' . $e->getMessage());

            return redirect()->route('admin.alerts.index')
                ->with('error', 'Unable to fetch alert for editing.');
        }
    }

    /**
     * Update the specified alert
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:security,maintenance,emergency,system,general',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'priority' => $request->priority,
                'status' => $request->status
            ];

            $response = $this->apiService->put(env('ONE_SRI_LANKA_API_URL') . "/alerts/{$id}", $data);

            if ($response && isset($response['success']) && $response['success']) {
                return redirect()->route('admin.alerts.index')
                    ->with('success', 'Alert updated successfully!');
            } else {
                $errorMessage = $response['message'] ?? 'Failed to update alert';
                return back()->withInput()
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error updating alert: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'An error occurred while updating the alert. Please try again.');
        }
    }

    /**
     * Remove the specified alert
     */
    public function destroy($id)
    {
        try {
            $response = $this->apiService->delete(env('ONE_SRI_LANKA_API_URL') . "/alerts/{$id}");

            // Check if response indicates success (either success=true or status=success)
            $isSuccess = (isset($response['success']) && $response['success']) || 
                        (isset($response['status']) && $response['status'] === 'success');
            
            // Check if response indicates error
            $isError = isset($response['status']) && $response['status'] === 'error';

            if ($isSuccess) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Alert deleted successfully!'
                    ]);
                }

                return redirect()->route('admin.alerts.index')
                    ->with('success', 'Alert deleted successfully!');
            } else {
                // Handle API errors
                $errorMessage = $response['message'] ?? 'Failed to delete alert';
                
                if ($isError) {
                    $errorMessage = "API Error: " . $errorMessage;
                }

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 400);
                }

                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting alert: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the alert: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'An error occurred while deleting the alert. Please try again.');
        }
    }

    /**
     * Get alerts data for DataTables (AJAX endpoint)
     */
    public function getData()
    {
        try {
            $response = $this->apiService->get(env('ONE_SRI_LANKA_API_URL') . '/alerts');

            if ($response && isset($response['success']) && $response['success']) {
                $alerts = collect($response['data'] ?? []);

                return response()->json([
                    'data' => $alerts->map(function ($alert) {
                        return [
                            'id' => $alert['id'] ?? '',
                            'title' => $alert['title'] ?? '',
                            'category' => $alert['category'] ?? '',
                            'priority' => $alert['priority'] ?? '',
                            'description' => $alert['description'] ?? '',
                            'status' => $alert['status'] ?? '',
                            'created_at' => $alert['created_at'] ?? '',
                            'actions' => view('admin.partials.alert-actions', compact('alert'))->render()
                        ];
                    })
                ]);
            } else {
                return response()->json(['data' => []]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching alerts data: ' . $e->getMessage());
            return response()->json(['data' => []]);
        }
    }

    /**
     * Toggle alert status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $response = $this->apiService->post(env('ONE_SRI_LANKA_API_URL') . "/alerts/{$id}/toggle-status");

            if ($response && isset($response['success']) && $response['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alert status updated successfully!'
                ]);
            } else {
                $errorMessage = $response['message'] ?? 'Failed to update alert status';
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error toggling alert status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the alert status.'
            ], 500);
        }
    }
}
