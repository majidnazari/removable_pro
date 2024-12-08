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
        Schema::create('family_boards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('category_content_id');


            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_content_id')->references('id')->on('category_contents')->onDelete('cascade');

            $table->string('title');
            $table->datetime('selected_date');
            $table->string('file_path')->nullable();
            $table->string('description');
            //$table->enum('status',["Active","Inactive","None"])->default("Active");

            $table->tinyInteger('status', )->default(0)->comment("1=Active 2=Inactive");   



            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_boards');
    }
};
