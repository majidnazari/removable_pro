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
        Schema::create('person_marriages', function (Blueprint $table) {
            
            $table->unsignedBigInteger('id', true)->primary(); 

            $table->unsignedBigInteger('creator_id'); // foreign key for the spouse
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('man_id')->index(); // foreign key
            $table->unsignedBigInteger('woman_id')->index(); // foreign key for the spouse
            
            
            // Define foreign keys
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('man_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('woman_id')->references('id')->on('people')->onDelete('cascade');
          

            
            //$table->foreign('man_id')->references('id')->on('people')->onDelete('cascade');
            //$table->foreign('woman_id')->references('id')->on('people')->onDelete('cascade')->nullable();
            //$table->enum('marriage_status',["Related","Notrelated","Suspend","None"])->default("None");
            //$table->enum('spouse_status',["Alive","Dead","Divorce","Unkown","None"])->default("None");
            //$table->enum('status',["Active","Inactive","None"])->default("Active");
            //$table->string('marriage_status', 20)->default("None");
            //$table->string('status', 20)->default("None");

            $table->tinyInteger('marriage_status', )->default(0)->comment(" 0=None 1=Related 2=Notrelated  3=Suspend ");   
            $table->tinyInteger('status', )->default(1)->comment("1=Active 2=Inactive");   



            $table->datetime('marriage_date')->nullable();
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
        Schema::dropIfExists('person_marriages');
    }
};
