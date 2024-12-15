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
        Schema::create('naslan_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('priority')->default(1);
            //$table->enum('status',["Active","Inactive","None"])->default("None");

            $table->tinyInteger('status', )->default(0)->comment("  1=Active 2=Inactive ");   

            
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naslan_relationships');
    }
};
