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
        Schema::create('middle_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('major_field_id');
            $table->foreign('major_field_id')->references('id')->on('major_fields')->onDelete('cascade');

            $table->string('title');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('middle_fields');
    }
};
