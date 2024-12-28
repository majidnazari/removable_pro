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
        Schema::create('family_events', function (Blueprint $table) {
            
            $table->id();
          
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('person_id')->index();
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('category_content_id');

            $table->unsignedBigInteger('group_category_id')->nullable();


            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('category_content_id')->references('id')->on('category_contents')->onDelete('cascade');

            $table->foreign('group_category_id')->references('id')->on('group_categories')->onDelete('cascade');

           

            $table->datetime('event_date');
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
        Schema::dropIfExists('family_events');
    }
};
