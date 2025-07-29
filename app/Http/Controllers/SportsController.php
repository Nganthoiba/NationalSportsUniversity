<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sport;
use Illuminate\Support\Facades\Auth;

class SportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sports = Sport::all();
        return view('sports.index', compact('sports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sport_name' => 'required|string|max:255',
            'sport_name_in_hindi' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::user()->_id;
        unset($data['_token']);
        
        Sport::create($data);
        return redirect()->route('sports.index')->with('success', 'Sport created successfully.');
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
        $sport = Sport::findOrFail($id);
        return view('sports.edit', compact('sport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'sport_name' => 'required|string|max:255',
            'sport_name_in_hindi' => 'nullable|string|max:255',
        ]);

        $department = Sport::findOrFail($id);
        $data = $request->all();
        $data['created_by'] = Auth::user()->_id;
        unset($data['_token']);
        $department->update($data);

        return redirect()->route('sports.index')->with('success', 'Sport updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Department::findOrFail($id)->delete();
        $sport = Sport::findOrFail($id);
        $sport->enabled = false;
        $sport->updated_by = Auth::user()->_id;
        $sport->save();
        return redirect()->route('sports.index')->with('success', 'Sport disabled successfully.');
    }

    public function enable(string $id)
    {
        $sport = Sport::findOrFail($id);
        $sport->enabled = true;
        $sport->updated_by = Auth::user()->_id;
        $sport->save();
        return redirect()->route('sports.index')->with('success', 'Sport enabled successfully.');
    }
}
