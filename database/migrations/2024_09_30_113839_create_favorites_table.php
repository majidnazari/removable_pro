<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('person_id')->index(); // foreign key favorite
            $table->unsignedBigInteger('group_category_id')->nullable();


            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('group_category_id')->references('id')->on('group_categories')->onDelete('cascade');




            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
           // $table->enum('star', ["1", "2", "3", "4", "5"])->nullable("5");
            //$table->enum('status', ["Active", "Inactive", "None"])->default("Active");

            $table->tinyInteger('star', )->default(0)->comment("0=None  1=One 2=Two 3=Three 4=Four 5=Five 6=Six 7=Seven 8=Eight 9=Nine 10=Ten");   
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
        Schema::dropIfExists('favorites');
    }
};
