<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:view roles|create roles|edit roles|delete roles']);
        $this->middleware('permission:view roles')->only('index');
        $this->middleware('permission:create roles')->only('create');
        $this->middleware('permission:edit roles')->only('edit');
        $this->middleware('permission:delete roles')->only('destroy');
    }

    // Show roles
    public function index() {
        
        $roles = Role::orderBy('created_at', 'DESC')->paginate(10);
        return view('roles.list', ['roles' => $roles]);
    }
    
    // Create roles
    public function create() {

        $permissions = Permission::orderBy('name', 'DESC')->get();
        return view('roles.create', ['permissions' => $permissions]);
    }

    // Store roles
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if($validator->passes()){
            $role = Role::create(['name' => $request->name]);

            if(!empty($request->permission)){
                foreach($request->permission as $name){
                    $role->givePermissionTo($name);
                }

            }
            return redirect()->route('roles.index')->with('success', 'Role create sucessfully.');
        }else{
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
        
    }

    // For editing role page
    public function edit($id) {
        $role = Role::findorFail($id);
        $hasPermissions = $role->permissions->pluck('name');

        $permissions = Permission::orderBy('name', 'DESC')->get();

        return view('roles.edit', ['role' => $role, 'hasPermissions' => $hasPermissions, 'permissions' => $permissions]);
    }

    // For update permision page
    public function update(Request $request, $id) {

        $role = Role::findorFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,'.$id.',id'
        ]);

        if($validator->passes()){

            $role->name = $request->name;
            $role->save();

            if(!empty($request->permission)){
                    $role->syncPermissions($request->permission);
            }else {
                $role->syncPermissions([]);
            }
            

            return redirect()->route('roles.index')->with('success', 'Role updated sucessfully.');
        }else{
            return redirect()->route('role.edit', $id)->withInput()->withErrors($validator);
        }
    }

    // For deleting permision page
    public function destroy(Request $request) {
        
        $id= $request->id;
        $role = Role::find($id);

        if($role == null){
            session()->flash('error', 'Role not found');
            return response()->json([
                'status' => false
            ]);
        }

        $role->delete();

            session()->flash('success', 'Role deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }

}
