<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mail;
use App\Models\Record;
use App\Models\MailPriority;
use App\Models\MailTypology;
use App\Models\MailType;
use App\Models\Author;
use App\Models\BatchMail;
use App\Models\MailArchiving;
use App\Models\MailContainer;
use App\Models\RecordStatus;
use App\Models\Term;
use App\Models\Slip;
use App\Models\SlipRecord;

class SearchMailController extends Controller
{
    public function index(Request $request)
    {   $mails = '';
        switch($request->input('categ')){
            case "dates":
                $exactDate = $request->input('date_exact');
                $startDate = $request->input('date_start');
                $endDate = $request->input('date_end');

                $query = mail::query();

                if ($exactDate) {
                    $query->whereDate('date', $exactDate);
                }

                if ($startDate && $endDate) {
                    $query->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->whereDate('date', '>=', $startDate)
                            ->whereDate('date', '<=', $endDate);
                    });
                }

                $mails = $query->get();
                break;

            case "typology":
                $mails = mail::where('mail_typology_id', $request->input('id'))
                    ->get();
                break;

            case "author":
                $mails = Mail::join('mail_author', 'mails.id', '=', 'mail_author.mail_id')
                    ->where('mail_author.author_id', $request->input('id'))
                    ->get();
                break;

            case "container":
                $mails = Mail::whereIn('id', MailArchiving::where('container_id', $request->input('id'))->pluck('mail_id'))
                    ->get();
                break;

            case "batch":
                $mails = Mail::whereIn('id', BatchMail::where('batch_id', $request->input('id'))->pluck('mail_id'))
                    ->get();
                break;

            default:
                $mails = mail::take(5)->get();
                break;
        }

        $priorities = MailPriority::all();
        $types = MailType::all();
        $typologies = MailTypology::all();
        $authors = Author::all();

        return view('mails.index', compact('mails', 'priorities', 'types', 'typologies', 'authors'));
    }


    public function date()
    {
        return view('search.mail.dateSearch');
    }
}

