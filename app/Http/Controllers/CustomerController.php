<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortField = $request->query('sortField', 'name');
        $sortDirection = $request->query('sortDirection', 'asc');
        $page = $request->query('page', 1);
        $perPage = 10;

        $query = Customer::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        }

        $query->orderBy($sortField, $sortDirection);

        $customers = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($customers);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $customer = Auth::guard('customer')->user();
            $token = $customer->createToken('Customer Access Token')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        $customer = new Customer([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            'address' => $request->address,
        ]);

        $customer->save();

        return response()->json(['message' => 'Customer registered successfully'], 201);
    }
    public function profile(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        $user = Auth::guard('customer')->user();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }


        $userData = [
            'id' => $user->id,
            'phone' => $user->phone,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'address' => $user->address,
        ];

        return response()->json($userData);
    }
    public function update(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 401);
        }
        $user = Auth::guard('customer')->user();
        if (!$user) {
            return response()->json([
                'message' => 'Người dùng không tìm thấy hoặc không được xác thực.'
            ], 401);
        }

        $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:15|unique:customers,phone,' . $user->id,
            'city' => 'string|max:255',
            'address' => 'string|max:255',
        ]);

        $user->name = $request->input('name', $user->name);
        $user->phone = $request->input('phone', $user->phone);
        $user->city = $request->input('city', $user->city);
        $user->address = $request->input('address', $user->address);

        $user->save();

        return response()->json([
            'message' => 'Cập nhật thông tin người dùng thành công.',
            'user' => $user
        ]);
    }
    public function changePassword(Request $request)
    {
        $user = Auth::guard('customer')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Người dùng không tìm thấy hoặc không được xác thực.'
            ], 401);
        }

        $request->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ]);

        if (!Hash::check($request->input('currentPassword'), $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu cũ không đúng.'
            ], 400);
        }

        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        return response()->json([
            'message' => 'Đổi mật khẩu thành công.',
        ]);
    }
}
