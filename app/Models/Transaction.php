<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'type',
        'value',
        'created_at',
        'description',
    ];

    public $timestamps = true;

    const UPDATED_AT = null;
}
