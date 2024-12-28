<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'number_account',
        'type',
        'value',
        'status',
        'created_at',
        'description',
    ];

    public $timestamps = true;

    const UPDATED_AT = null;

    public function account()
    {
        return $this->belongsTo(Account::class, 'number_account', 'number_account');
    }
}
