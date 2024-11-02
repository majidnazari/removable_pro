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
        Schema::create('clans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('biggest_person_id')->nullable();

            $table->foreign('creator_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('biggest_person_id')->references('id')->on('people')->onDelete('cascade'); 

            $table->string('title');
            $table->string('clan_exact_family_name');
            $table->string('clan_code'); //like start from Clan001  to Clan00n

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clans');
    }
};
