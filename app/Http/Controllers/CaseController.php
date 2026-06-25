<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    
    public function index()
    {
        $cases = CaseModel::with('officer')->orderBy('case_id')->get();
        return view('cases.index', compact('cases'));
    }

  
    public function create()
    {
        $officers = User::all();
        return view('cases.create', compact('officers'));
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_title'       => 'required|string|max:150',
            'case_type'        => 'nullable|string|max:50',
            'case_description' => 'nullable|string|max:1000',
            'case_status'      => 'required|in:OPEN,CLOSED,PENDING',
            'officer_id'       => 'required|exists:users,user_id',
        ]);

        CaseModel::create($validated);

        return redirect()->route('cases.index')->with('success', 'Case created successfully.');
    }

    
    public function edit(CaseModel $case)
    {
        $officers = User::all();
        return view('cases.edit', compact('case', 'officers'));
    }

   
    public function update(Request $request, CaseModel $case)
    {
        $validated = $request->validate([
            'case_title'       => 'required|string|max:150',
            'case_type'        => 'nullable|string|max:50',
            'case_description' => 'nullable|string|max:1000',
            'case_status'      => 'required|in:OPEN,CLOSED,PENDING',
            'officer_id'       => 'required|exists:users,user_id',
        ]);

        $case->update($validated);

        return redirect()->route('cases.index')->with('success', 'Case updated successfully.');
    }

   
    public function destroy(CaseModel $case)
    {
        $case->delete();
        return redirect()->route('cases.index')->with('success', 'Case deleted successfully.');
    }
}
