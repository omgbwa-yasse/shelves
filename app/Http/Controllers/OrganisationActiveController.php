<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\OrganisationActive;
use App\models\UserOrganisation;
use App\models\Organisation;
use Illuminate\Support\Facades\Auth;

class OrganisationActiveController extends Controller
{



        public function index()
        {
            $organisationActives = OrganisationActive::with('organisation', 'user')->get();
            return view('organisations.active.index', compact('organisationActives'));
        }




        public function create()
        {
            $user_organisations = [];
            foreach(Auth::user()->organisations as $organisation){
                $user_organisations[] = $organisation->id;
            }
            $organisations = Organisation::whereNotIn('id', $user_organisations)->get();
            return view('organisations.active.create', compact('organisations'));
        }




        public function store(Request $request)
        {
            OrganisationActive::create($request->all());
            return redirect()->route('organisation-active.index');
        }



        public function show()
        {
            $organisationActive = organisationActive::where('user_id',auth()->user()->getAuthIdentifier());
            return view('organisations.active.show', compact('organisationActive'));
        }




        public function edit(INT $id)
        {
            $organisationActive = OrganisationActive::where('user_id', $id)->first();
            $organisations = UserOrganisation::with(['user', 'organisation'])
                ->where('user_id', auth()->id())
                ->get();
            return view('organisations.active.edit', compact('organisationActive', 'organisations'));

        }




        public function update(Request $request, OrganisationActive $organisationActive)
        {
            $organisationActive->update($request->all());
            return redirect()->route('organisation-active.index');
        }




        public function destroy(OrganisationActive $organisationActive)
        {
            $organisationActive->delete();
            return redirect()->route('organisation-active.index');
        }


}
