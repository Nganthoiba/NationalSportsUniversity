<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $currentRole = session('currentRole');
        switch($currentRole->role_name){
            case 'Super Admin':
                //$roles = Role::whereNotIn('role_name', ['Super Admin'])->get();
                $roles = Role::get();
                break;
            case 'University Admin':
                $roles = Role::whereNotIn('role_name', ['Super Admin', 'University Admin'])->get();
                break;
        }
        //$roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_description' => 'nullable|string|max:255',
            'permission_names' => 'nullable|array',            
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->_id;
        $data['changeable'] = true;
        $data['enabled'] = true;
        unset($data['_token']);

        Role::create($data);

        return redirect()->route('roles.index')->with(['success' => true, 'message'=>'Role created successfully.']);
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'role_description' => 'nullable|string|max:255',
            'permission_names' => 'nullable|array',
        ]);

        $role = Role::findOrFail($id);
        $role->permission_names = $request->permission_names??[];
        $role->role_name = $request->role_name;
        $role->role_description = $request->role_description;
        $role->updated_by = Auth::user()->_id;

        $role->save();

        return redirect()->route('roles.index')->with(['success' => true, 'message'=>'Role updated successfully.']);
    }

    public function destroy(string $id)
    {
        //Role::findOrFail($id)->delete();
        $role = Role::findOrFail($id);
        $role->enabled = false;
        $role->updated_by = Auth::user()->_id;
        $role->save();
        return redirect()->route('roles.index')->with(['success'=>true, 'message'=>'Role disabled successfully.']);
    }

    public function enable(string $id)
    {
        $role = Role::findOrFail($id);
        $role->enabled = true;
        $role->updated_by = Auth::user()->_id;
        $role->save();
        return redirect()->route('roles.index')->with(['success'=>true, 'message'=>'Role enabled successfully.']);
    }
}
