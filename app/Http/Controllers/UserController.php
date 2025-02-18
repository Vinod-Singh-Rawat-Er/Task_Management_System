<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller //implements HasMiddleware
{
    // public static function middleware(): array
    // {
    //     return [
    //         // examples with aliases, pipe-separated names, guards, etc:
    //         new Middleware('permission:view users', only: ['index']),
    //         new Middleware('permission:create users', only: ['create']),
    //         new Middleware('permission:edit users', only: ['edit']),
    //         new Middleware('permission:delete users', only: ['destroy'])
    //     ];
    // }
    public function __construct()
    {
        // $this->middleware(['permission:view users|create users|edit users|delete users']);
        $this->middleware('permission:view users')->only('index');
        $this->middleware('permission:create users')->only('create');
        $this->middleware('permission:edit users')->only('edit');
        $this->middleware('permission:delete users')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate();
        return view('users.list', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3','max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'same:confirm-password'],
            'confirm-password' => ['required'],
        ]);

        if($validator->fails()){
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->syncRoles($request->role);
            return redirect()->route('users.index')->with('success', 'User updated sucessfully.');
        
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
    public function edit(string $id)
    {
        $user = User::findorFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('id');
        return view('users.edit', ['user' => $user, 'roles' => $roles, 'hasRoles' => $hasRoles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findorFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);

        if($validator->fails()){
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $user->syncRoles($request->role);

            return redirect()->route('users.index')->with('success', 'User role updated sucessfully.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id= $request->id;
        $user = User::find($id);

        if($tasks == null){
            session()->flash('error', 'User not found');
            return response()->json([
                'status' => false
            ]);
        }

        $user->delete();

            session()->flash('success', 'User deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }
}
