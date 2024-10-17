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
        Schema::create('person_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id'); 
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('person_id')->index();
            $table->unsignedBigInteger('score_id')->index();

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('score_id')->references('id')->on('scores')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
 
            $table->enum('score_level',["Excellent","Verygood","Good","NotBad","Bad","None"])->default("None");
            $table->enum('status',["Active","a","None"])->default("Active");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_scores');
    }
};
