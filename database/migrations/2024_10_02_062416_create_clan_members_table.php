<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Clan_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id'); 
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('Clan_id');
            $table->string('family_code');

            $table->foreign('creator_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('Clan_id')->references('id')->on('Clans')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Clan_members');
    }
};
