<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $table = 'transfers';
    protected $fillable = [
        'number_account_origin',
        'number_account_destination',
        'amount',
        'description',
        'created_at',
    ];

    public $timestamps = true;

    const UPDATED_AT = null;
}
