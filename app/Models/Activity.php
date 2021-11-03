<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';
    public $timestamps = false;

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'users_id',
        'type',
        'title',
        'message',
        'link',
        'seen',
    ];

    /**
     * A Activity belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
