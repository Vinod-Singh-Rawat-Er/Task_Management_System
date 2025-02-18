<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:view tasks|create tasks|edit tasks|delete tasks']);
        $this->middleware('permission:view tasks')->only('index');
        $this->middleware('permission:create tasks')->only('create');
        $this->middleware('permission:edit tasks')->only('edit');
        $this->middleware('permission:delete tasks')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::latest()->paginate(10);

        return view('tasks.list', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $users =User::all();
        return view('tasks.create', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'due_date' => 'required',
            'assigned_user' => 'required'

        ]);

        if($validator->passes()){
            $tasks = new Task();
            $tasks->title = $request->title;
            $tasks->due_date = $request->due_date;
            $tasks->assigned_user = $request->assigned_user;
            $tasks->description = $request->description;
            $tasks->save();

            return redirect()->route('tasks.index')->with('success', 'Tasks create sucessfully.');
        }else{
            return redirect()->route('tasks.create')->withInput()->withErrors($validator);
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
    public function edit(string $id)
    {
        $task = Task::findorFail($id);
        $users =User::all();
        return view('tasks.edit', ['task' => $task, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tasks = Task::findorFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'due_date' => 'required',
            'assigned_user' => 'required'
        ]);

        if($validator->passes()){

            $tasks->title = $request->title;
            $tasks->due_date = $request->due_date;
            $tasks->assigned_user = $request->assigned_user;
            $tasks->description = $request->description;
            $tasks->save();

            return redirect()->route('tasks.index')->with('success', 'Task updated sucessfully.');
        }else{
            return redirect()->route('tasks.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id= $request->id;
        $tasks = Task::find($id);

        if($tasks == null){
            session()->flash('error', 'Task not found');
            return response()->json([
                'status' => false
            ]);
        }

        $tasks->delete();

            session()->flash('success', 'Task deleted successfully.');
            return response()->json([
                'status' => true
            ]);
    }
}
