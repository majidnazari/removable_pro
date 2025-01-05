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
        Schema::create('talent_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id')->index()->nullable();
            $table->unsignedBigInteger('talent_header_id')->index();
            $table->unsignedBigInteger('micro_field_id')->index();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('talent_header_id')->references('id')->on('talent_headers')->onDelete('cascade');
            $table->foreign('micro_field_id')->references('id')->on('micro_fields')->onDelete('cascade');

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
        Schema::dropIfExists('talent_details');
    }
};
