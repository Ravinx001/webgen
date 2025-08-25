<?php

namespace App\Http\Controllers\User\Auth;

use App\Facades\OSLAuthServiceFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_name' => ['required', 'min:6', 'max:32'],
            'password' => ['required', 'string', 'min:6', 'max:32'],
        ]);

        $response = OSLAuthServiceFacade::authenticate($credentials);

        if (!empty($response['error'])) {
            return redirect()->back()
                ->withInput(['user_name' => request('user_name')])
                ->withErrors(array_merge(
                    ['global' => $response['message'] ?? 'Authentication failed'],
                    $validationResult['errors'] ?? []
                ));
        }

       return redirect()->route('dashboard')->with('success', 'Login successful');

    }
}
