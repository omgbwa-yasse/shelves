<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mail;
use App\Models\Record;
use App\Models\MailPriority;
use App\Models\MailTypology;
use App\Models\MailType;
use App\Models\Author;
use App\Models\CommunicationRecord;
use App\Models\RecordStatus;
use App\Models\Term;
use App\Models\Slip;
use App\Models\SlipRecord;

class SearchController extends Controller
{
    public function index(Request $request){
        switch($request['search_type']){
            case 'record' : return $this->record($request);
            case 'mail' : return $this->mail($request);
            case 'communication' : return $this->communication($request);
            case 'communication_record' : return $this->communicationRecord($request);
            case 'transferring' : return $this->transferring($request);
            case 'transferring_record' : return $this->transferringRecord($request);
            default: return $this->default($request);
        }
    }

    public function record(Request $request)
    {
        $query = $request->input('query');
        $records = Record::query();

        if ($request->input('advanced') == true) {
            $records = $records->where(function ($queryBuilder) use ($query) {
                $fields = [
                    'date_start', 'date_end', 'date_exact', 'biographical_history',
                    'archival_history', 'acquisition_source', 'appraisal', 'accrual',
                    'arrangement', 'access_conditions', 'reproduction_conditions',
                    'language_material', 'characteristic', 'finding_aids',
                    'location_original', 'location_copy', 'related_unit',
                    'publication_note', 'note', 'archivist_note', 'rule_convention'
                ];

                foreach ($fields as $field) {
                    $queryBuilder->orWhere($field, 'LIKE', "%$query%");
                }
            });
        } else {
            // Ensure you have a search method on the Record model
            $records = $records->where('name', 'LIKE', "%$query%");
        }

        $records = $records->get();
        $statuses = RecordStatus::all();
        $terms = Term::all();

        return view('records.index', compact('records', 'statuses', 'terms'));
    }

    public function communication(Request $request)
    {
        $query = $request->input('query');
        $communications = Record::where('name', 'LIKE', "%$query%")->get(); // Corrected variable name

        return view('search.communication.slip', compact('communications'));
    }

    public function communicationRecord(Request $request)
    {
        $query = $request->input('query');
        $communicationRecords = CommunicationRecord::where('name', 'LIKE', "%$query%")->get(); // Corrected variable name

        return view('search.communication.record', compact('communicationRecords'));
    }

    public function mail(Request $request)
    {
        $query = $request->input('query');

        if ($request->input('advanced') == false) {
            $mails = Mail::where('name', 'LIKE', "%$query%")->get();
        } elseif ($categ = $request->input('categ')) {
            switch ($categ) {
                case "dates":
                    $mails = Mail::where('date', 'LIKE', "%{$request->input('date')}%")->get();
                    break;
                case "typology":
                    $mails = Mail::where('typology_id', $request->input('id'))->get();
                    break;
                case "author":
                    $mails = Mail::where('author_id', $request->input('id'))->get();
                    break;
                case "container":
                    $mails = Mail::where('container_id', $request->input('id'))->get();
                    break;
                default:
                    $mails = Mail::where('code', 'LIKE', "%$query%")
                        ->orWhere('name', 'LIKE', "%$query%")
                        ->orWhere('description', 'LIKE', "%$query%")
                        ->get();
                    break;
            }
        } else {
            $mails = Mail::where('code', 'LIKE', "%$query%")
                ->orWhere('name', 'LIKE', "%$query%")
                ->orWhere('description', 'LIKE', "%$query%")
                ->get();
        }

        $priorities = MailPriority::all();
        $types = MailType::all();
        $typologies = MailTypology::all();
        $authors = Author::all();

        return view('mails.index', compact('mails', 'priorities', 'types', 'typologies', 'authors'));
    }

    public function transferring(Request $request)
    {
        $query = $request->input('query');

        if ($request->input('advanced') == true) {
            $slips = Slip::where('name', 'LIKE', "%$query%")->get();
        } else {
            $slips = Slip::where('name', 'LIKE', "%$query%")
                ->orWhereHas('officer', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->get();
        }

        return view('transferrings.slips.index', compact('slips'));
    }

    public function transferringRecord(Request $request)
    {
        $query = $request->input('query');

        if ($request->input('advanced') == true) {
            $records = SlipRecord::where('date_start', 'LIKE', "%$query%")
                ->orWhere('date_end', 'LIKE', "%$query%")
                ->orWhere('date_exact', 'LIKE', "%$query%")
                ->orWhereHas('level', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhereHas('slip', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhereHas('support', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhereHas('activity', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhereHas('container', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })->get();
        } else {
            $records = SlipRecord::where('name', 'LIKE', "%$query%")->get();
        }

        $records->load('slip');

        return view('search.transferring.record', compact('records'));
    }

    public function default(Request $request)
    {
        $query = $request->input('query');
        $records = Record::where('name', 'LIKE', "%$query%")
            ->orWhere('code', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->latest()->take(4)
            ->get();

        $mails = Mail::where('name', 'LIKE', "%$query%")
            ->orWhere('code', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->latest()->take(4)
            ->get();

        $transferrings = Slip::where('name', 'LIKE', "%$query%")
            ->orWhere('code', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->latest()->take(4)
            ->get();

        $transferringRecords = SlipRecord::where('name', 'LIKE', "%$query%")
            ->orWhere('code', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->latest()->take(4)
            ->get();

        return view('search.index', compact('records', 'mails', 'transferrings', 'transferringRecords'));
    }
}
