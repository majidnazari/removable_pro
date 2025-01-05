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
        Schema::create('talent_headers', function (Blueprint $table) {

            $table->id('id');
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id')->index()->nullable();
            $table->unsignedBigInteger('group_category_id');
            $table->unsignedBigInteger('person_id');

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_category_id')->references('id')->on('group_categories')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');

            $table->string('title');
            $table->string('end_date');
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
        Schema::dropIfExists('talent_headers');
    }
};
