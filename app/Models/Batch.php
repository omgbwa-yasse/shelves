<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'organisation_holder_id',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_holder_id');
    }

    public function mails()
    {
        return $this->belongsToMany(BatchMail::class, 'batch_mail', 'mail_id', 'batch_id');
    }


}

