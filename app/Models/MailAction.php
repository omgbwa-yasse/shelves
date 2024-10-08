<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailAction extends Model
{
    use HasFactory;


    protected $table = 'mail_actions';

    protected $fillable = [
        'name',
        'description',
        'to_return',
        'description',
    ];

    protected $casts = [
        'to_return' => 'boolean',
    ];

    public function transaction()
    {
        return $this->hasMany(MailTransaction::class, 'action_id');
    }

}
