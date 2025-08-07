<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {        
        if(!Auth::user()->hasPermission('view_role')){
            return view('layout.errorMessage',[
                'title' => 'Unauthorized',
                'message' => 'Sorry, you do not have permission to view roles.'
            ]);
        }

        $currentRole = session('currentRole');
        switch($currentRole->role_name){
            case 'Super Admin':
                //$roles = Role::whereNotIn('role_name', ['Super Admin'])->get();
                $roles = Role::orderBy('role_name')->get();
                break;
            default:
                //$roles = Role::get();
                $roles = Role::whereNotIn('role_name', ['Super Admin'])
                ->whereNotLike('role_name','University Admin%')
                ->orderBy('role_name')->get();
        }
        //$roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {        
        if(!Auth::user()->hasPermission('add_role')){
            return view('layout.errorMessage',[
                'title' => 'Unauthorized',
                'message' => 'Sorry, you do not have permission to add roles.'
            ]);
        }
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
        //Trying to get role_id from session
        $currentRole = session('currentRole');
        $role = Role::find($currentRole->id);

        if($role->role_name != 'Super Admin' && !$role->hasPermission('edit_role')){
            return view('layout.errorMessage',[
                'title' => 'Unauthorized',
                'message' => 'Sorry, you do not have permission to edit roles.'
            ]);
        }

        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        //Trying to get role_id from session
        $currentRole = session('currentRole');
        $role = Role::find($currentRole->id);

        if($role->role_name != 'Super Admin' && !$role->hasPermission('edit_role')){
            return view('layout.errorMessage',[
                'title' => 'Unauthorized',
                'message' => 'Sorry, you do not have permission to edit roles.'
            ]);
        }

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
        //Trying to get role_id from session
        $currentRole = session('currentRole');
        $role = Role::find($currentRole->id);

        if($role->role_name != 'Super Admin' && !$role->hasPermission('enable_or_disable_role')){            
            return redirect()->route('roles.index')->with(['error'=>true, 
            'message'=>'Sorry, you do not have permission to enable or disable roles.']);
        }

        //Role::findOrFail($id)->delete();
        $role = Role::findOrFail($id);
        $role->enabled = false;
        $role->updated_by = Auth::user()->_id;
        $role->save();
        return redirect()->route('roles.index')->with(['success'=>true, 'message'=>'Role disabled successfully.']);
    }

    public function enable(string $id)
    {
        //Trying to get role_id from session
        $currentRole = session('currentRole');
        $role = Role::find($currentRole->id);

        if($role->role_name != 'Super Admin' && !$role->hasPermission('enable_or_disable_role')){            
            return redirect()->route('roles.index')->with(['error'=>true, 
            'message'=>'Sorry, you do not have permission to enable or disable roles.']);
        }

        $role = Role::findOrFail($id);
        $role->enabled = true;
        $role->updated_by = Auth::user()->_id;
        $role->save();
        return redirect()->route('roles.index')->with(['success'=>true, 'message'=>'Role enabled successfully.']);
    }
}
