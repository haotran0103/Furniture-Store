<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return response()->json($coupons);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:coupons,name',
            'value' => 'required|integer|min:1|max:100',
            'expiry_date' => 'required|date',
        ]);

        $coupon = Coupon::create($validatedData);

        return response()->json($coupon, 201);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|required|unique:coupons,name,' . $id,
            'value' => 'sometimes|required|integer|min:1|max:100',
            'expiry_date' => 'sometimes|required|date',
        ]);

        $coupon->update($validatedData);

        return response()->json($coupon);
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json(null, 204);
    }
    public function applyCoupon($couponCode)
    {

        $coupon = Coupon::where('name', $couponCode)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã coupon không hợp lệ.'
            ]);
        }

        $discountValue = $coupon->value;

        return response()->json([
            'expiry_date' => $coupon->expiry_date,
            'success' => true,
            'discount' => $discountValue,
            'message' => 'Áp dụng mã coupon thành công.'
        ]);
    }
}
