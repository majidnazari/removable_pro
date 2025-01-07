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
        Schema::create('talent_detail_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id')->index()->nullable();
            $table->unsignedBigInteger('participating_user_id')->index();
            $table->unsignedBigInteger('talent_detail_id')->index();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('participating_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('talent_detail_id')->references('id')->on('talent_details')->onDelete('cascade');

            $table->tinyInteger('score', )->default(0)->comment("0=None 1=One 2=Two 3=Three 4-Four 5=Five 6=Six 7=Seven 8=Eight 9=Nine 10=Ten"); 
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
        Schema::dropIfExists('talent_detail_scores');
    }
};
