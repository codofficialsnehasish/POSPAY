<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class AdminController extends Controller  implements HasMiddleware
{
    
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Admin View', only: ['index','show']),
            new Middleware('permission:Admin Create', only: ['create','store']),
            new Middleware('permission:Admin Edit', only: ['edit','update']),
            new Middleware('permission:Admin Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
   
        $users = User::role('Admin')->latest()->get();
            
        return view('admin.admin.index',compact('users'));
    }

    public function create()
    {
        
        return view('admin.admin.create');
    }

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
               

            ]);
    
       
            if ($request->hasFile('image')) {
                $user->addMedia($request->file('image'))->toMediaCollection('admin-image');
            }
            $user->syncRoles("Admin");
            if($user->save()){

                return redirect()->route('admin.index')->with('success', 'Admin Created Successfully');
            }else{
                return back()->with('success','Admin Not Added');
            }
        }
    }


    public function edit(string $id)
    {

        $admin = User::findOrFail($id);
       
        return view('admin.admin.edit',compact('admin'));
    }

       public function update(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // die;
        
        $admin = User::findOrFail($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email,'. $admin->id,
            'phone' => 'required|digits:10|regex:/^[6789]/|unique:users,phone,'. $admin->id,
            'address'=>'required',
            'status' => 'required|in:1,0'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
   
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->status = $request->status;

            if(isset($request->password)){
                $admin->password = bcrypt($request->password);
            }


            if ($request->hasFile('image')) {
                $vendor->clearMediaCollection('admin-image');
                $vendor->addMedia($request->file('image'))->toMediaCollection('admin-image');
            }
       
            if( $admin->save()){
                return back()->with('success','Admin Updated Successfully');
            }else{
                return back()->with('success','Admin Not Updated');
            }
        }
    }

    
}
