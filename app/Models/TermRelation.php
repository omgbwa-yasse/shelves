<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Term;
use App\Models\TermCategory;
use App\Models\RelationType;



class TermRelation extends Model
{

    use HasFactory;

    protected $table = 'term_relations';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];


    public function terms()
    {
        return $this->hasMany(Term::class);
    }


}

