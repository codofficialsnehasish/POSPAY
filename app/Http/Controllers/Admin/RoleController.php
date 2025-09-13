<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Faker\Provider\ar_EG\Person;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Role', only: ['index','show']),
            new Middleware('permission:Create Role', only: ['create','store']),
            new Middleware('permission:Edit Role', only: ['edit','update']),
            new Middleware('permission:Delete Role', only: ['destroy']),
            new Middleware('permission:Asign Permission', only: ['addPermissionToRole','givePermissionToRole'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::get();
        return view('admin.role-permission.role.index',compact('roles'));
    }



 

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:roles,name'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $role = Role::create(['name' => $request->name]);
                if($role){
                    return back()->with(['success'=>'Role Created Successfully']);
                }else{
                    return back()->with(['error'=>'Role Not Created']);
                }
            }

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
      }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$roleId)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => ['required','string',Rule::unique('roles')->ignore($roleId),]
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }else{
                $role = Role::findOrFail($roleId);
                $role->update([
                    'name' => $request->name
                ]);
            
            }
     
            return redirect()->back()->with('success','Role Updated Successfully');
        }  catch (ValidationException $e) {
         return redirect()->back()->withErrors($e->errors())->withInput();
      }

    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found.'], 404);
        }
        if ($role->users()->exists()) {
            return response()->json(['error' => 'Cannot delete this role because it is associated with users.'], 422);
        }
        $role->delete();
        return response()->json(['success' => 'Role deleted successfully.']);
    }


    public function addPermissionToRole($roleId){
        // $permissions = Permission::get();
        $permissions = Permission::where('guard_name', 'web')
        ->orderBy('group_name')
        ->get();
 
        $role = Role::findOrFail($roleId);
        $rolePermissions = DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id',$role->id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();

        return view('admin.role-permission.role.add-permissions',[
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId){
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);
        return redirect()->back()->with('success','Permissions Added to Role Successfully');
    }
}
