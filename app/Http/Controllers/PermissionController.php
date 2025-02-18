<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:view permissions|create permissions|edit permissions|delete permissions']);
        $this->middleware('permission:view permissions')->only('index');
        $this->middleware('permission:create permissions')->only('create');
        $this->middleware('permission:edit permissions')->only('edit');
        $this->middleware('permission:delete permissions')->only('destroy');
    }
    // For showing permision page
    public function index() {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
        return view('permissions.list', ['permissions' => $permissions]);
    }

    // For creating permision page
    public function create() {
        return view('permissions.create');
    }

    // For storing permision page
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if($validator->passes()){
            Permission::create(['name' => $request->name]);
            return redirect()->route('permission.index')->with('success', 'Permission create sucessfully.');
        }else{
            return redirect()->route('permission.create')->withInput()->withErrors($validator);
        }
    }

    // For editing permision page
    public function edit($id) {
        $permissions = Permission::findorFail($id);

        return view('permissions.edit', ['permissions' => $permissions]);
    }

    // For update permision page
    public function update(Request $request, $id) {
        $permissions = Permission::findorFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id'
        ]);

        if($validator->passes()){
            $permissions->name = $request->name;
            $permissions->save();

            return redirect()->route('permission.index')->with('success', 'Permission updated sucessfully.');
        }else{
            return redirect()->route('permission.edit', $id)->withInput()->withErrors($validator);
        }
    }

    // For deleting permision page
    public function destroy(Request $request) {
        
        $id= $request->id;
        $permissions = Permission::find($id);

        if($permissions == null){
            session()->flash('error', 'Permissions not found');
            return response()->json([
                'status' => false
            ]);
        }

        $permissions->delete();

            session()->flash('success', 'Permissions deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }
}
