<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerMaster;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SellerMasterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Seller Master View', only: ['index','show']),
            new Middleware('permission:Seller Master Create', only: ['create','store']),
            new Middleware('permission:Seller Master Edit', only: ['edit','update']),
            new Middleware('permission:Seller Master Delete', only: ['destroy']),
        ];
    }

    // List all seller masters
    public function index()
    {
        $seller_masters = SellerMaster::where('vendor_id', auth()->user()->id)->get();
        return view('admin.seller_master.index', compact('seller_masters'));
    }

    // Show form to create a seller
    public function create()
    {
        return view('admin.seller_master.create');
    }

    // Store new seller
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seller_name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seller = new SellerMaster();
        $seller->seller_name = $request->seller_name;
        $seller->vendor_id = auth()->user()->id;
        $seller->email = $request->email;
        $seller->phone = $request->phone;
        $seller->address = $request->address;
        $seller->city = $request->city;
        $seller->state = $request->state;
        $seller->country = $request->country;
        $seller->gst_number = $request->gst_number;
        $seller->status = $request->status;

        $res = $seller->save();

        if ($res) {
            return redirect()->back()->with('success', 'Seller added successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to add seller');
        }
    }

    // Show single seller (optional)
    public function show(string $id)
    {
        $seller = SellerMaster::findOrFail($id);
        return view('admin.seller_master.show', compact('seller'));
    }

    // Show form to edit seller
    public function edit(string $id)
    {
        $seller = SellerMaster::findOrFail($id);
        return view('admin.seller_master.edit', compact('seller'));
    }

    // Update seller
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'seller_name' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seller = SellerMaster::findOrFail($id);
        $seller->seller_name = $request->seller_name;
        $seller->email = $request->email;
        $seller->phone = $request->phone;
        $seller->address = $request->address;
        $seller->city = $request->city;
        $seller->state = $request->state;
        $seller->country = $request->country;
        $seller->gst_number = $request->gst_number;
        $seller->status = $request->status;

        $res = $seller->update();

        if ($res) {
            return redirect()->back()->with('success', 'Seller updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update seller');
        }
    }

    // Delete seller
    public function destroy(string $id)
    {
        $seller = SellerMaster::find($id);
        if ($seller) {
            $res = $seller->delete();
            if ($res) {
                return back()->with('success', 'Seller deleted successfully');
            } else {
                return back()->with('error', 'Failed to delete seller');
            }
        } else {
            return back()->with('error', 'Seller not found');
        }
    }
}
