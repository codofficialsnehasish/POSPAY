<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class PermissionsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Permission', only: ['index','show']),
            new Middleware('permission:Create Permission', only: ['create','store']),
            new Middleware('permission:Edit Permission', only: ['edit','update']),
            new Middleware('permission:Delete Permission', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::where('guard_name', 'web')
        ->orderBy('group_name')
        ->get();
        return view('admin.role-permission.permission.index',compact('permissions'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
            'group_name' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
          $permission=  Permission::Create([
                'name' => $request->name,
                'group_name' => $request->group_name
                
            ]);
            if($permission){
                return back()->with(['success'=>'Permission Created Successfully']);
            }else{
                return back()->with(['error'=>'Permission Not Created']);
            }
        }
           
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

    }
    
    public function storePermissionGroup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }else{
    
            $groupName = $request->group_name;
            $actions = ['Create','View','Edit', 'Update', 'Delete'];
        
            foreach ($actions as $action) {
              $permission=  Permission::firstOrCreate([
                    'name' => $groupName . ' ' . $action,
                    'group_name' => $groupName, 
                ]);
            }
            if($permission){
                return back()->with('success', 'Permission Group created successfully with actions.');
            }else{
                return back()->with(['error'=>'Permission Not Created']);
            }
        }
    
       
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('role-permission.permission.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            try{
              
                $validator = Validator::make($request->all(), [
                    'name' => ['required','string',Rule::unique('permissions')->ignore($id),],
                    'group_name' => 'required'

                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator->errors())->withInput();
                }else{
                    $permission = Permission::findOrFail($id);
                    $permission->update([
                        'name' => $request->name,
                        'group_name' => $request->group_name
                    ]);
        
                    
                }
            
                return redirect()->back()->with('success','Permission Updated Successfully');
            } catch (ValidationException $e) {
                return redirect()->back()->withErrors($e->errors())->withInput();
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
    
        if (!$permission) {
            return response()->json(['error' => 'Permission not found.'], 404);
        }
    
        if ($permission->roles()->exists()) {

            foreach ($permission->roles as $role) {
                if ($role->users()->exists()) {
                    return response()->json(['error' => 'Cannot delete this permission because it is assigned to a role that has associated users.'], 422);
                }
            }
            return response()->json(['error' => 'Cannot delete this permission because it is assigned to roles.'], 422);
        }
    
        $permission->delete();
    
        return response()->json(['success' => 'Permission deleted successfully.']);
    }
}
