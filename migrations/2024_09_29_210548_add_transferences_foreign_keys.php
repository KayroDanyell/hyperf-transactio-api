<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class AddTransferencesForeignKeys extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transferences', function (Blueprint $table) {
            $table->foreign('payer_id')->references('id')->on('users');
            $table->foreign('payee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transferences', function (Blueprint $table) {
            $table->dropForeign(['payer_id']);
            $table->dropForeign(['payee_id']);
        });
    }
}
