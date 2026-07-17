<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::orderBy('equipment_id')->paginate(10);
        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_name' => 'required|string|max:150',
            'equipment_type' => 'nullable|string|max:50',
            'serial_no'      => 'nullable|string|max:100|unique:equipment,serial_no',
        ]);

        Equipment::create($validated);
        return redirect()->route('equipment.index')->with('success', 'Equipment added.');
    }

    // Log usage + auto flip status via trigger
    public function logUsage(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        EquipmentUsage::create([
            'equipment_id' => $equipment->equipment_id,
            'used_by'      => Auth::id(),
            'remarks'      => $validated['remarks'] ?? null,
        ]);

        return back()->with('success', 'Usage logged; equipment marked In Use.');
    }

    public function release(Equipment $equipment)
    {
        $equipment->update(['availability_status' => 'AVAILABLE']);
        return back()->with('success', 'Equipment released and marked Available.');
    }
}