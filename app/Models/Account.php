<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    protected $fillable = [
        'number_account',
        'balance',
        'limit_credit',
        'created_at',
        'idTransaction',
    ];

    public $timestamps = true;

    const UPDATED_AT = null;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'idTransaction');
    }
}
