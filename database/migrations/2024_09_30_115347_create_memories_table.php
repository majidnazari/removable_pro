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
        Schema::create('memories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('creator_id'); 
            $table->unsignedBigInteger('editor_id')->nullable();
            
            $table->unsignedBigInteger('person_id')->index();
            $table->unsignedBigInteger('category_content_id')->index()->nullable();
            $table->unsignedBigInteger('group_category_id')->index();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');            
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('category_content_id')->references('id')->on('category_contents')->onDelete('cascade');
            $table->foreign('group_category_id')->references('id')->on('group_categories')->onDelete('cascade');
            

            $table->string('content')->nullable();// according to the category content may be voice or video or pic or simple text
            $table->string('title');
            $table->string('description')->nullable();
            $table->boolean('is_shown_after_death')->default(0);
            //$table->enum('status',["Active","Inactive","None"])->default("Active");

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
        Schema::dropIfExists('memories');
    }
};
