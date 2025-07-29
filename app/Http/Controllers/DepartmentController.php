<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dept_name' => 'required|string|max:255',
            'dept_name_in_hindi' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->_id;
        unset($data['_token']);
        
        Department::create($data);
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'dept_name' => 'required|string|max:255',
            'dept_name_in_hindi' => 'nullable|string|max:255',
        ]);

        $department = Department::findOrFail($id);
        $data = $request->all();
        $data['created_by'] = Auth::user()->_id;
        unset($data['_token']);
        $department->update($data);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(string $id)
    {
        //Department::findOrFail($id)->delete();
        $department = Department::findOrFail($id);
        $department->enabled = false;
        $department->updated_by = Auth::user()->_id;
        $department->save();
        return redirect()->route('departments.index')->with('success', 'Department disabled successfully.');
    }

    public function enable(string $id)
    {
        $department = Department::findOrFail($id);
        $department->enabled = true;
        $department->updated_by = Auth::user()->_id;
        $department->save();
        return redirect()->route('departments.index')->with('success', 'Department enabled successfully.');
    }
}
