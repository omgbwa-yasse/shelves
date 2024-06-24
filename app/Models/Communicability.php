<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communicability extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'duration',
        'decription',
    ];


    public function activities()
    {
        return $this->hasMany(activity::class);
    }


}


