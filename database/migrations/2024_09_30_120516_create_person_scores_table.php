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
            $table->unsignedBigInteger('group_category_id')->nullable();


            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
 
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('score_id')->references('id')->on('scores')->onDelete('cascade');
            $table->foreign('group_category_id')->references('id')->on('group_categories')->onDelete('cascade');

           
           // $table->enum('score_level',["Excellent","Verygood","Good","NotBad","Bad","None"])->default("None");
          // $table->enum('status',["Active","a","None"])->default("Active");

            $table->tinyInteger('score_level', )->default(0)->comment("0=None  1=Bad 2=NotBad 3=Good 4=Verygood 5=Excellent");   
            $table->tinyInteger('status', )->default(1)->comment("1=Active 2=Inactive");   

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
