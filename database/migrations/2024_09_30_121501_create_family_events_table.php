<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('family_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id')->index();
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');

            $table->datetime('event_date');
            $table->enum('status',["Active","Inactive","None"])->default("Active");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_events');
    }
};
