<?php

namespace App\Http\Controllers;
use App\Models\Mail;
use App\Models\Record;
use App\Models\MailPriority;
use App\Models\MailTypology;
use App\Models\MailType;
use App\Models\Author;
use App\Models\communicationRecord;
use App\Models\MailTransaction;
use App\Models\RecordStatus;

use App\Models\Slip;
use App\Models\SlipRecord;
use App\Models\documentType;
use App\Models\MailAction;
use App\Models\User;
use App\Models\MailStatus;
use App\Models\Organisation;
use App\Models\UserOrganisation;
use App\Models\MailAttachment;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SearchMailFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $deadline = $request->input('deadline');

        if ($type == 'true' && $deadline == 'available' ) {

            $transactions = MailTransaction::whereHas('action', function ($query) {
                $query->where('to_return', true);
            })->get();

        }
        elseif($type == 'true' && $deadline == 'exceeded'){

            $transactions = MailTransaction::whereHas('action', function ($query) {
                $query->whereRaw('DATE_ADD(mail_transactions.date_creation, INTERVAL mail_actions.duration SECOND) > NOW()');
            })->get();

        }else{

            $transactions = MailTransaction::whereHas('action', function ($query) {
                $query->where('to_return', false);
            })->get();
        }



        $transactions->load('mail', 'action', 'organisationReceived', 'organisationSend');
        return view('mails.send.index', compact('transactions'));
    }




}
