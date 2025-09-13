<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\UserVendor;

class UsersController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:User View', only: ['index','show']),
            new Middleware('permission:User Create', only: ['create','store']),
            new Middleware('permission:User Edit', only: ['edit','update']),
            new Middleware('permission:User Delete', only: ['destroy']),
        ];
    }



    public function index()
    {
        $users = User::role('User')->latest()->get();
        return view('admin.users.index',compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $vendors = User::role('Vendor')->latest()->get();
        return view('admin.users.create',compact('roles','vendors'));
    }

    public function store(Request $request)
    {
        
        $vendor_id =Auth::guard('web')->user()->id;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone',
            'password' => 'required|min:8',
            'status' => 'required|in:1,0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $user = User::create([
                'name' => $request->first_name.' '.$request->last_name,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email' => $request->email,
                'phone'=>$request->phone,
                'password' => Hash::make($request->password),
                'status'=>$request->status,
                'vendor_id'=>$vendor_id
            ]);
            if ($request->has('vendors')) 
            {
                foreach ($request->vendors as $vendor) {

    
                    UserVendor::create([
                        'user_id' => $user->id,
                        'vendor_id' => $vendor
                    ]);
                }
            }
            if ($request->hasFile('image')) {
                $user->addMedia($request->file('image'))->toMediaCollection('user-image');
            }
            $user->syncRoles("User");
            if($user->save()){
                return redirect()->route('user.index')->with('success', 'User Created Successfully');
            }else{
                return back()->with('success','User Not Added');
            }
        }
    }

    public function edit(string $id)
    {
        $data = User::findOrFail($id);
        $roles = Role::get();
        $userRoles = $data->roles->pluck('name')->toArray();
        $vendors = User::role('Vendor')->latest()->get();
        // echo "<pre>";
        // print_r($userRoles);
        // die;
        $selected_vendors = UserVendor::where('user_id', $data->id)->pluck('vendor_id')->toArray();
        return view('admin.users.edit',compact('data','roles','userRoles','vendors','selected_vendors'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone,'. $user->id,
            'password' => 'nullable|min:8',
            'status' => 'required|in:1,0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->status = $request->status;
            $user->email = $request->email;
            $user->phone = $request->phone;
            if(isset($request->password)){
                $user->password = bcrypt($request->password);
            }
            if ($request->has('vendors')) {
                UserVendor::where('user_id', $user->id)->delete();
                foreach ($request->vendors as $vendor) {
                    UserVendor::create([
                        'user_id' => $user->id,
                        'vendor_id' => $vendor
                    ]);
                }
            }
            if ($request->hasFile('image')) {
                $user->clearMediaCollection('user-image');
                $user->addMedia($request->file('image'))->toMediaCollection('user-image');
            }
            // $user->syncRoles($request->roles);
            if( $user->save()){
                return back()->with('success','User Updated Successfully');
            }else{
                return back()->with('success','User Not Updated');
            }
        }
    }
}
