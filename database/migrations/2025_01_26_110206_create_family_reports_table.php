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
        Schema::create('family_reports', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'clan_id')->nullable();
            $table->integer(column: 'men_count')->nullable();
            $table->integer(column: 'women_count')->nullable();
            $table->integer(column: 'oldest')->nullable();
            $table->integer(column: 'max_longevity')->nullable();
            $table->integer(column: 'youngest')->nullable();
            $table->integer(column: 'marriage_count')->nullable();
            $table->integer(column: 'divorce_count')->nullable();
            $table->string(column: 'last_update')->nullable();
            $table->boolean(column: 'change_flag')->default(false);
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
        Schema::dropIfExists('family_reports');
    }
};
