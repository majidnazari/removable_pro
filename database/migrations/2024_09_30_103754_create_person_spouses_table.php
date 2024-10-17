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
        Schema::create('person_spouses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('person_id')->index(); // foreign key
            $table->unsignedBigInteger('spouse_id')->index(); // foreign key for the spouse
            $table->unsignedBigInteger('creator_id'); // foreign key for the spouse
            $table->unsignedBigInteger('editor_id')->nullable();
            
            // Define foreign keys
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('spouse_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');

            
            //$table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            //$table->foreign('spouse_id')->references('id')->on('people')->onDelete('cascade')->nullable();
            $table->enum('marrage_status',["Related","Notrelated","Suspend","None"])->default("None");
            $table->enum('spouse_status',["Alive","Dead","Divorce","Unkown","None"])->default("None");
            $table->enum('status',["Active","Inactive","None"])->default("Active");

            $table->datetime('marrage_date')->nullable();
            $table->datetime('divorce_date')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_spouses');
    }
};
