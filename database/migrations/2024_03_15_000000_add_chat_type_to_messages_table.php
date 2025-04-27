<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('chat_type')->default('group');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->dropColumn('conversation_id');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('chat_type');
            $table->dropForeign(['recipient_id']);
            $table->dropColumn('recipient_id');
            $table->string('conversation_id');
        });
    }
}; 