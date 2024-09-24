<?php

namespace App\Http\Controllers;


use App\Http\Requests\CommunicationRequest;
use App\Models\Communication;
use App\Models\CommunicationStatus;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{


    public function index()
    {
        $communications = Communication::with('operator', 'operatorOrganisation','records','user', 'userOrganisation')->paginate(10);
        return view('communications.index', compact('communications'));
    }




    public function create()
    {
        $users = User::all();
        $statuses = CommunicationStatus::all();
        $organisations = Organisation::all();
        return view('communications.create', compact('users', 'statuses','organisations'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:communications,code',
            'name' => 'required|string|max:200',
            'content' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'return_date' => 'required|date',
            'user_organisation_id' => 'required|exists:organisations,id',
            'return_effective' => 'nullable|date',
            'status_id' => 'required|exists:communication_statuses,id',
        ]);

        Communication::create([
            'code' => $request->code,
            'name' => $request->name,
            'content' => $request->input('content'),
            'operator_id' => Auth()->user()->id,
            'user_id' => $request->user_id,
            'user_organisation_id' => $request->user_organisation_id,
            'operator_organisation_id' => Auth()->user()->organisation->id,
            'return_date' => $request->return_date,
            'return_effective' => $request->return_effective,
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Communication created successfully.');
    }



    public function show(INT $id)
    {
        $communication = Communication::with('operator', 'operatorOrganisation', 'user', 'userOrganisation')->findOrFail($id);
        return view('communications.show', compact('communication'));
    }




    public function edit(Communication $communication)
    {
        $users = User::all();
        $statuses = CommunicationStatus::all();
        return view('communications.edit', compact('communication', 'users', 'statuses'));
    }




    public function update(Request $request, Communication $communication)
    {
        $request->validate([
            'code' => 'required|unique:communications,code,' . $communication->id,
            'name' => 'required|string|max:200',
            'content' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'return_date' => 'required|date',
            'return_effective' => 'nullable|date',
            'status_id' => 'required|exists:communication_statuses,id',
        ]);

        $communication->update([
            'code' => $request->code,
            'name' => $request->name,
            'content' => $request->input('content'),
            'operator_id' => Auth()->user()->id,
            'operator_organisation_id' => Auth()->user()->organisation->id,
            'user_id' => $request->user_id,
            'user_organisation_id' => $request->user_organisation_id,
            'return_date' => $request->return_date,
            'return_effective' => $request->return_effective,
            'status_id' => $request->status_id,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Communication updated successfully.');
    }



    public function destroy(Communication $communication)
    {
        $communication->delete();
        return redirect()->route('transactions.index')->with('success', 'Communication deleted successfully.');
    }
}


