<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerMaster;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::guard('web')->user();
        if ($user->hasRole('Super Admin')) {
            $seller_masters = SellerMaster::all();
        }else if($user->hasRole('Admin')){
            $seller_masters = SellerMaster::where('admin_id', auth()->user()->id)->get();
        }else if($user->hasRole('Vendor')){
            $seller_masters = SellerMaster::where('admin_id', auth()->user()->admin_id)->where('status',1)->get();
        }else{
            $seller_masters = collect();
        }
        return view('admin.seller_master.index', compact('seller_masters'));
    }

    // Show form to create a seller
    public function create()
    {
        $user = Auth::guard('web')->user();

        $admins = collect();
        $branches = collect();
        if ($user->hasRole('Super Admin')) {
            $admins = User::role('Admin')->get();
        } else if($user->hasRole('Admin')){
            $branches = User::role('Vendor')->where('admin_id',$user->id)->latest()->get();
        }
        return view('admin.seller_master.create',compact('admins','branches','user'));
    }

    public function get_branches(Request $request, $id){
        $branches = User::role('Vendor')->where('admin_id',$id)->latest()->get();

        return response()->json([
            'status' => true,
            'branches' => $branches
        ]);
    }

    // Store new seller
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seller_name' => 'required|max:255',
            'email' => 'nullable|email|max:255|unique:seller_masters,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
            'admin_id' => 'required',
            'vendor_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seller = new SellerMaster();
        $seller->seller_name = $request->seller_name;
        $seller->vendor_id = $request->vendor_id ?? null;
        $seller->admin_id = $request->admin_id;
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
        $user = Auth::guard('web')->user();

        $admins = collect();
        $branches = collect();
        if ($user->hasRole('Super Admin')) {
            $admins = User::role('Admin')->get();
        } else if($user->hasRole('Admin')){
            $branches = User::role('Vendor')->where('admin_id',$user->id)->latest()->get();
        }

        $seller = SellerMaster::findOrFail($id);
        return view('admin.seller_master.edit', compact('seller','admins','branches','user'));
    }

    // Update seller
    public function update(Request $request, string $id)
    {
        $seller = SellerMaster::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'seller_name' => 'required|max:255',
            'email' => 'nullable|email|max:255|unique:seller_masters,email,'.$seller->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'required|in:0,1',
            'admin_id' => 'required',
            'vendor_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seller->vendor_id = $request->vendor_id ?? null;
        $seller->admin_id = $request->admin_id;
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
