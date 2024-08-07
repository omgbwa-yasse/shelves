<?php

namespace App\Http\Controllers;

use App\Models\ReservationStatus;
use Illuminate\Http\Request;

class ReservationStatusController extends Controller
{
    public function index()
    {
        $statuses = ReservationStatus::with('reservations')->get();
        return view('reservations.statuses.index', compact('statuses'));
    }

    public function show(INT $id)
    {
        $status = ReservationStatus::findOrFail($id);
        return view('reservations.statuses.show', compact('status'));
    }

    public function create()
    {
        return view('reservations.statuses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:reservation_statuses|max:50',
            'description' => 'nullable|string',
        ]);

        ReservationStatus::create($request->all());

        return redirect()->route('reservation-status.index')
            ->with('success', 'Reservation status created successfully.');
    }

    public function edit(INT $id)
    {
        $status = ReservationStatus::findOrFail($id);
        return view('reservations.statuses.edit', compact('status'));
    }

    public function update(Request $request, ReservationStatus $status)
    {
        $request->validate([
            'name' => 'required|unique:reservation_statuses,name,'.$status->id.'|max:50',
            'description' => 'nullable|string',
        ]);

        $status->update($request->all());

        return redirect()->route('reservation-status.index')
            ->with('success', 'Reservation status updated successfully.');
    }

    public function destroy(ReservationStatus $status)
    {
        $status->delete();

        return redirect()->route('reservation-status.index')
            ->with('success', 'Reservation status deleted successfully.');
    }
}
