<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    protected $primaryKey = 'number_account';
    protected $fillable = [
        'number_account',
        'balance',
        'limit_credit',
        'created_at',
    ];

    public $timestamps = true;

    const UPDATED_AT = null;

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'number_account', 'number_account');
    }
}
