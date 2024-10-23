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
        Schema::create('person_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('person_id'); // foreign key
            //$table->unsignedBigInteger('person_id'); // foreign key
           // $table->unsignedBigInteger('person_id'); // foreign key

           
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            

            //$table->string('birth_location')->nullable(); 
            //$table->string('address')->nullable();       
            $table->string('profile_picture')->nullable();    
            $table->enum('physical_condition', ["Healthy", "Handicapped","Dead", "None"])->default("Healthy");    
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_details');
    }
};
