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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id'); // foreign key
            $table->unsignedBigInteger('editor_id')->nullable(); // foreign key
            $table->unsignedBigInteger('person_id'); // foreign key
            $table->unsignedBigInteger('country_id')->nullable(); // foreign key
            $table->unsignedBigInteger('province_id')->nullable(); // foreign key
            $table->unsignedBigInteger('city_id')->nullable(); // foreign key
            $table->unsignedBigInteger('area_id')->nullable(); // foreign key

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');


            $table->string('location_title')->nullable(); 
            $table->string('street_name')->nullable(); 
            $table->integer('builder_no')->nullable(); 
            $table->integer('floor_no')->nullable(); 
            $table->integer('unit_no')->nullable(); 
            //$table->string('address')->nullable();        
            //$table->string('image')->nullable();          
            $table->decimal('lat')->nullable();
            $table->decimal('lon')->nullable();
            //$table->enum('gendar', ["Male", "Female", "None"])->default("None");   --person_detail
            //$table->enum('physical_condition', ["Healthy", "Handicapped", "None"])->default("Healthy"); --person_detail
            $table->enum('status', ["Active", "Inactive", "Suspend", "None"])->default("None");

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
