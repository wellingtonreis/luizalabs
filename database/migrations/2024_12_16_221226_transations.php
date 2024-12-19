<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('number_account');
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->decimal('value', 8, 2);
            $table->timestamp('created_at');
            $table->string('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
