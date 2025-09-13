<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Coach;
use App\Models\Branch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class VendorController extends Controller  implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Vendor View', only: ['index','show']),
            new Middleware('permission:Vendor Create', only: ['create','store']),
            new Middleware('permission:Vendor Edit', only: ['edit','update']),
            new Middleware('permission:Vendor Delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
   
        $users = User::role('Vendor')->latest()->get();
            
        return view('admin.vendor.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coaches = Coach::get();
        $user = Auth::guard('web')->user();

        $admins = [];
        if ($user->hasRole('Super Admin')) {
            $admins = User::role('Admin')->get(); 
        }

        return view('admin.vendor.create', compact('coaches', 'admins', 'user'));
    }


    public function coach_create(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);

        }else{
            $coach = Coach::create([
                'name' => $request->name,
            ]);



            return response()->json([
                'success' => true,
                'message' => 'Branch created successfully.',
                'coach' => [
                    'id' => $coach->id,
                    'name' => $coach->name,
                ],
            ]);
        
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // die;

        $admin_id =Auth::guard('web')->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'password' => 'required|min:8',
            'store_id'=>'required',
            'store_location'=>'required',
            'address'=>'required',
            'status' => 'required|in:1,0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone'=>$request->phone,
                'password' => Hash::make($request->password),
                'status'=>$request->status,
                'address'=>$request->address,
                'store_number'=>$request->store_id,
                'store_location'=>$request->store_location,
                'gst_no'=>$request->gst_no,
                'admin_id'=>$request->admin_id,

            ]);
            if ($user) {
        
                if ($request->has('branch')) {
                    foreach ($request->branch as $coach) {
        
                        Branch::create([
                            'user_id' => $user->id,
                            'coach_id' => $coach
                        ]);
                    }
                }
            }
       
            if ($request->hasFile('image')) {
                $user->addMedia($request->file('image'))->toMediaCollection('vendor-image');
            }
            $user->syncRoles("Vendor");
            if($user->save()){

                return redirect()->route('vendor.index')->with('success', 'Vendor Created Successfully');
            }else{
                return back()->with('success','Vendor Not Added');
            }
        }
    }


    public function edit(string $id)
    {
        $coaches = Coach::get();
        $vendor = User::findOrFail($id);
        $selected_branches = Branch::where('user_id', $vendor->id)->pluck('coach_id')->toArray();

        $user = Auth::guard('web')->user();

        $admins = [];
        if ($user->hasRole('Super Admin')) {
            $admins = User::role('Admin')->get(); 
        }


        return view('admin.vendor.edit',compact('vendor','coaches','selected_branches','admins','user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // die;
        
        $vendor = User::findOrFail($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,'. $vendor->id,
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone,'. $vendor->id,
            'store_id'=>'required',
            'store_location'=>'required',
            'address'=>'required',
            'status' => 'required|in:1,0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
   
            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->address = $request->address;
            $vendor->store_number = $request->store_id;
            $vendor->store_location = $request->store_location;
            $vendor->gst_no = $request->gst_no;
            $vendor->status = $request->status;
            $vendor->admin_id = $request->admin_id ;

            if(isset($request->password)){
                $vendor->password = bcrypt($request->password);
            }

    
            if ($request->has('branch')) {
                Branch::where('user_id', $vendor->id)->delete();
                foreach ($request->branch as $coach_id) {
                    Branch::create([
                        'user_id' => $vendor->id,
                        'coach_id' => $coach_id,
                    ]);
                }
            }
            if ($request->hasFile('image')) {
                $vendor->clearMediaCollection('vendor-image');
                $vendor->addMedia($request->file('image'))->toMediaCollection('vendor-image');
            }
       
            if( $vendor->save()){
                return back()->with('success','Vendor Updated Successfully');
            }else{
                return back()->with('success','Vendor Not Updated');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
