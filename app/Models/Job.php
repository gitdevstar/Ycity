<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'clients_fk',
        'name',
        'description',
        'location',
        'street',
        'plz',
        'categories_fk',
        'subcategories_fk',
        'specifications',
        'skills',
        'complexities_fk',
        'payment_types_fk',
        'status_fk',
        'cost',
        'attachments',
        'eventdate',
        'end',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];
}
