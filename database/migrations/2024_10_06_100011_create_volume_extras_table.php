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
        Schema::create('volume_extras', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('day_number')->default(365);
            $table->string('description')->nullable();
            $table->enum('status',["Active","Inactive","None"])->default("Active");

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volume_extras');
    }
};
