<?php

namespace App\Http\Controllers;

use App\Models\communicationRecord;
use App\Models\ReservationRecord;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Communication;
use App\Models\ReservationStatus;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('operator', 'user', 'status', 'userOrganisation', 'operatorOrganisation')->get();
        return view('reservations.index', compact('reservations'));
    }



    public function show($id)
    {
        $reservation = reservation::findOrFail($id);
        return view('reservations.show', compact('reservation'));
    }


    public function approved(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reservations,id'
        ]);

        DB::beginTransaction();
        try {
            // Récupérer la réservation avec ses relations
            $reservation = Reservation::with(['records', 'user', 'userOrganisation'])
                ->findOrFail($request->input('id'));

            // Créer la nouvelle communication
            $communication = Communication::create([
                'code' => $reservation->code,
                'name' => $reservation->name,
                'content' => $reservation->content,
                'operator_id' => auth()->id(),
                'user_id' => $reservation->user_id,
                'user_organisation_id' => $reservation->user_organisation_id,
                'operator_organisation_id' => auth()->user()->current_organisation_id,
                'return_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'status_id' => 1,
            ]);

            // Pour chaque record de la réservation
            foreach ($reservation->records as $record) {
                // Créer l'entrée dans communication_record
                CommunicationRecord::create([
                    'communication_id' => $communication->id,
                    'record_id' => $record->id,
                    'content' => null, // ou une valeur par défaut si nécessaire
                    'is_original' => true, // ou false selon votre logique
                    'return_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                    'return_effective' => null
                ]);

                // Supprimer l'entrée de reservation_record correspondante
                ReservationRecord::where([
                    'reservation_id' => $reservation->id,
                    'record_id' => $record->id
                ])->delete();
            }

            // Supprimer la réservation
            $reservation->delete();

            DB::commit();

            return redirect()
                ->route('transactions.show', $communication->id)
                ->with('success', 'La réservation a été approuvée et convertie en communication.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de l\'approbation de la réservation: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de l\'approbation: ' . $e->getMessage());
        }
    }




    public function create()
    {
        $operators = User::all();
        $users = User::all();
        $statuses = ReservationStatus::all();
        $organisations = Organisation::all();
        return view('reservations.create', compact('operators', 'users', 'statuses', 'organisations'));
    }





    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:reservations|max:10',
            'name' => 'required|string|max:200',
            'content' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'user_organisation_id' => 'required|exists:organisations,id',
        ]);


        Reservation::create([
            'code' => $request->code,
            'name' => $request->name,
            'content' => $request->input('content'),
            'operator_id' => Auth()->user()->id,
            'user_id' => $request->user_id,
            'user_organisation_id' => $request->user_organisation_id,
            'operator_organisation_id' => Auth()->user()->current_organisation_id,
            'return_date' => $request->return_date,
            'status_id' => 1, // Examen
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation created successfully.');
    }



    public function edit(INT $id)
    {
        $reservation = reservation::findOrFail($id);
        $operators = User::all();
        $users = User::all();
        $statuses = ReservationStatus::all();
        $organisations = Organisation::all();
        return view('reservations.edit', compact('reservation', 'operators', 'users', 'statuses', 'organisations'));
    }




    public function update(Request $request, INT $id)
    {
        $reservation = reservation::findOrFail($id);
        $request->validate([
            'code' => 'required|unique:reservations,code,'.$reservation->id.'|max:10',
            'name' => 'required|string|max:200',
            'content' => 'nullable|sting',
            'user_id' => 'required|exists:users,id',
            'user_organisation_id' => 'required|exists:organisations,id',
        ]);

        $reservation->update([
            'code' => $request->code,
            'name' => $request->name,
            'content' => $request->input('content'),
            'operator_id' => Auth()->user()->id,
            'operator_organisation_id' => Auth()->user()->current_organisation_id,
            'user_id' => $request->user_id,
            'user_organisation_id' => $request->user_organisation_id,
            'status_id' => 1,
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }




    public function destroy(INT $id)
    {
        $reservation = reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }
}
