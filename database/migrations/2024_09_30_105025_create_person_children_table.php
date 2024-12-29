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
        Schema::create('person_children', function (Blueprint $table) {
            
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('person_marriage_id')->index();
            $table->unsignedBigInteger('child_id')->index();
            
            $table->foreign('person_marriage_id')->references('id')->on('person_marriages')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');


            // $table->enum('child_kind',["Direct_child","Mother_child","Father_child","Adoption","None"])->default("Direct_child");
            // $table->enum('child_status',["With_family","Separated","None"])->default("With_family");
            // $table->enum('status',["Active","Inactive","None"])->default("Active");

            $table->tinyInteger('child_kind', )->default(0)->comment("0=None  1=Direct_child 2=Mother_child 3=Father_child 4=Adoption ");   
            $table->tinyInteger('child_status', )->default(0)->comment("0=None  1=With_family 2=Separated");   
            $table->tinyInteger('status', )->default(1)->comment("1=Active 2=Inactive");   


            //$table->datetime('birth_date')->nullable();
            //$table->datetime('death_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_children');
    }
};
