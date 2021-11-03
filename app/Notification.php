<?php
namespace App;

use App\Events\PostCreated;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => \App\Events\NewNotification::class,
    ];
}
