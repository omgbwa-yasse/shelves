<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Models\Record;
use App\Models\RecordSupport;
use App\Models\RecordStatus;
use App\Models\Container;
use App\Models\Activity;
use App\Models\Term;
use App\Models\Accession;
use App\Models\Author;
use App\Models\RecordLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class lifeCycleController extends Controller
{

    public function recordToRetain()
    {
        $title = "actifs";

        $records = Record::with('activity.retentions')
            ->whereHas('activity.retentions', function ($query) {
                $query->whereRaw('DATE_ADD(created_at, INTERVAL duration YEAR) < NOW()');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('records.index', compact('records', 'title'));
    }


    public function recordToTransfer()
    {
        $title = "à transférer";
        $records = Record::with('activity')->get();
        $records = Record::whereHas('activity.retentions', function ($query) {
            $query->whereRaw('DATE_ADD(created_at, INTERVAL duration YEAR) > NOW()');
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('records.index', compact('records', 'title'));
    }



    public function recordToSort()
    {
        $title = "à trier";
        $records = Record::with('activity.retentions.sort')
            ->whereHas('activity.retentions', function ($query) {
                $query->whereRaw('DATE_ADD(created_at, INTERVAL duration YEAR) > NOW()')
                    ->whereHas('sort', function ($query) {
                        $query->where('code', 'T');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('records.index', compact('records', 'title'));
    }


    public function recordToKeep()
    {
        $title = "à conserver";

        $records = Record::with('activity.retentions.sort')
            ->whereHas('activity.retentions', function ($query) {
                $query->whereRaw('DATE_ADD(created_at, INTERVAL duration YEAR) > NOW()')
                    ->whereHas('sort', function ($query) {
                        $query->where('code', 'C');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('records.index', compact('records', 'title'));
    }



    public function recordToEliminate()
    {
        $title = "à éliminer";

        $records = Record::with('activity.retentions.sort')
            ->whereHas('activity.retentions', function ($query) {
                $query->whereRaw('DATE_ADD(created_at, INTERVAL duration YEAR) > NOW()')
                    ->whereHas('sort', function ($query) {
                        $query->where('code', 'D');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('records.index', compact('records', 'title'));
    }


}
