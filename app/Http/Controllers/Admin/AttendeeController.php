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

class AttendeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Attendee View', only: ['index']),
            new Middleware('permission:Attendee Create', only: ['create','store']),
            new Middleware('permission:Attendee Edit', only: ['edit','update']),
            new Middleware('permission:Attendee Delete', only: ['destroy']),
        ];
    }
    public function index()
    {
        $users = User::role('Attendee')->latest()->get(); 

        return view('admin.attendee.index',compact('users'));
    }

    public function create()
    {

        return view('admin.attendee.create');
    }

    public function store(Request $request)
    {
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
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'name' => $request->first_name.' '.$request->last_name,
                'email' => $request->email,
                'phone'=>$request->phone,
                'password' => Hash::make($request->password),
                'status'=>$request->status,
            ]);
            if ($request->hasFile('image')) {
                $system_user->addMedia($request->file('image'))->toMediaCollection('attendee-image');
            }

            $user->syncRoles('Attendee');
            $user->save();

            if($user->save()){
                return back()->with('success','Attendee Added Successfully');
            }else{
                return back()->with('success','Attendee Not Added');
            }
        }
    }

    public function edit(string $id)
    {
        $data = User::findOrFail($id);
        $roles = Role::get();
 
        return view('admin.attendee.edit',compact('data','roles'));
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
            if ($request->hasFile('image')) {
                $user->clearMediaCollection('attendee-image');
                $user->addMedia($request->file('image'))->toMediaCollection('attendee-image');
            }
            $user->syncRoles($request->roles);
            if( $user->save()){
                return back()->with('success','Attendee Updated Successfully');
            }else{
                return back()->with('success','Attendee Not Updated');
            }
        }
    }
}
