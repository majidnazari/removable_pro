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
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('person_spouse_id')->index();
            $table->unsignedBigInteger('child_id')->index();
            
            $table->foreign('person_spouse_id')->references('id')->on('person_spouses')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');


            $table->enum('child_kind',["Direct_child","Mother_child","Father_child","Adoption","None"])->default("Direct_child");
            $table->enum('child_status',["With_family","Seperated","None"])->default("With_family");
            $table->enum('status',["Active","InActive","None"])->default("Active");
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
