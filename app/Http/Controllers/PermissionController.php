<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create',[
            'mode' => 'create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'label' => 'required|string',
            'description' => 'required|string',
        ], [
            'name.required' => 'Permission name is required.',
            'name.unique' => 'This permission name already exists.',
            'label.required' => 'Permission label is required.',
            'description.required' => 'Permission description is required.',
        ]);

        Permission::create($request->only('name', 'label', 'description'));

        return redirect()->route('permissions.index')->with(['success' => true, 'message' => 'Permission created.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $data = [
            'permission' => $permission,
            'mode' => 'edit'
        ];
        return view('permissions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'label' => 'required|string',
            'description' => 'required|string',
            'group' => 'nullable|string',
        ], [
            'label.required' => 'Permission label is required.',
            'description.required' => 'Permission description is required.',
        ]);

        $permission->update($request->only('label', 'description', 'group'));

        return redirect()->route('permissions.index')->with(['success' => true, 'message' => 'Permission updated.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        $permission->enabled = $request->enabled=="true"? true:false;
        $permission->save();
        $message = $request->enabled == "true"?"Permission has been enabled.":"Permission has been disabled.";
        //dd($permission);
        return redirect()->route('permissions.index')->with(['success' => true, 'message' => $message]);
    }

    //now assign permissions to a role
    public function assignPermissionsToRole(Request $request, $role_id){
        $role = Role::find($role_id);
        if(empty($role)){
            return view('layout.errorMessage', [
                'title' => 'Role not found!',
                'message' => 'The role you have passed is not available in our application system.'
            ], 404);
        }

        //Retrieving available permissions
        $permissions = Permission::where('enabled', true)->get();

        return view('permissions.assign_permissions_to_role',[
            'role' => $role,
            'permissions' => $permissions
        ]);
    }
}
