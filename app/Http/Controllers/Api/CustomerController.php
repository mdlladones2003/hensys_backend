<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Requests\CustomerLoginRequest;
use App\Http\Requests\CustomerUpdateProfileRequest;
use App\Http\Requests\CustomerChangePasswordRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    // Register a new customer
    public function register(CustomerRegisterRequest $request)
    {
        $validated = $request->validated();

        $customer = Customer::create([
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email'         => $validated['email'],
            'password'      => $validated['password'],
            'phone'         => $validated['phone'] ?? null,
            'address'       => $validated['address'] ?? null,
            'customer_type' => $validated['customer_type'] ?? 'regular'
        ]);

        $token = $customer->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message'   => 'Customer registered successfully',
            'customer'  => $customer,
            'token'     => $token
        ], 201);
    }

    // Login customer
    public function login(CustomerLoginRequest $request)
    {
        $validated = $request->validated();

        $customer = Customer::where('email', $validated['email'])->first();

        if (!$customer || !Hash::check($validated['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // Optional: Delete old tokens
        $customer->tokens()->delete();

        $token = $customer->createToken('mobile-app', ['customer:read', 'customer:write'])->plainTextToken;

        return response()->json([
            'message'   => 'Login successful',
            'customer'  => $customer,
            'token'     => $token
        ]);
    }

    // Logout customer (revoke current token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // Logout from all devices (revoke all tokens)
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices'
        ]);
    }

    // Get authenticated customer
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // Update customer profile
    public function updateProfile(CustomerUpdateProfileRequest $request)
    {
        $validated = $request->validated();

        $customer = $request->user();
        $customer->update($validated);

        return response()->json([
            'message'   => 'Profile updated successfully',
            'customer'  => $customer->fresh()
        ]);
    }

    // Change password
    public function changePassword(CustomerChangePasswordRequest $request)
    {
        $validated = $request->validated();

        $customer = $request->user();

        if (!Hash::check($validated['current_password'], $customer->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.']
            ]);
        }

        $customer->update([
            'password' => $validated['new_password']
        ]);

        // Optional: Revoke all tokens after password change
        $customer->tokens()->delete();

        return response()->json([
            'message' => 'Password changed successfully. Please login again.'
        ]);
    }
}
