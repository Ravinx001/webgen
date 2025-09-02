<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'data' => $alerts]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:security,maintenance,emergency,system,general',
            'priority' => 'required|in:low,medium,high,critical',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        $alert = Alert::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'active',
            'created_by' => $request->user()->id ?? null,
        ]);
        return response()->json(['success' => true, 'data' => $alert]);
    }

    public function show($id)
    {
        $alert = Alert::find($id);
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Alert not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $alert]);
    }

    public function update(Request $request, $id)
    {
        $alert = Alert::find($id);
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Alert not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:security,maintenance,emergency,system,general',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:active,inactive',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }
        $alert->update($request->only(['title', 'description', 'category', 'priority', 'status']));
        return response()->json(['success' => true, 'data' => $alert]);
    }

    public function destroy($id)
    {
        $alert = Alert::find($id);
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Alert not found'], 404);
        }
        $alert->delete();
        return response()->json(['success' => true, 'message' => 'Alert deleted successfully!']);
    }

    public function getData()
    {
        $alerts = Alert::all();
        return response()->json(['success' => true, 'data' => $alerts]);
    }

    public function toggleStatus($id)
    {
        $alert = Alert::find($id);
        if (!$alert) {
            return response()->json(['success' => false, 'message' => 'Alert not found'], 404);
        }
        $alert->status = $alert->status === 'active' ? 'inactive' : 'active';
        $alert->save();
        return response()->json(['success' => true, 'message' => 'Alert status updated successfully!']);
    }
}
