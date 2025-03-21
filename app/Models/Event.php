<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bulletin_board_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'status',
        'user_id'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function bulletinBoard()
    {
        return $this->belongsTo(BulletinBoard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

