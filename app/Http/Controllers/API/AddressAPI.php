<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Address;

class AddressAPI extends Controller
{
    public function save_address(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|digits:10|regex:/^[6789]/',
            'address' => 'nullable|string|max:255',
            'pincode' => 'nullable|digits:6',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'address_type' => 'nullable|in:home,office',
            'is_default' => 'nullable|boolean',
            'formatted_address' => 'nullable|string|max:255',
            'street_addresses' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-90,90',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $data['user_id'] = $request->user()->id;

        // Use your model to save the data
        $address = Address::create($data);

        // Return a response
        return response()->json([
            'success' => 'true',
            'message' => 'Address saved successfully!',
            'data' => $address,
        ], 201);
    }

    public function save_as_default_address(Request $request){
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:addresses,id',
            'is_default' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $address = Address::find($request->address_id);
        $address->is_default = $request->is_default;
        $res = $address->update();
        if($res){
            return response()->json([
                'success' => 'true',
                'message' => 'Address Updated successfully!',
                'data' => $address,
            ], 201);
        }
    }

    public function get_saved_address(Request $request){
        $address = Address::where('user_id',$request->user()->id)->get();

        return response()->json([
            'success' => 'true',
            'data' => $address,
        ], 201);
    }
}