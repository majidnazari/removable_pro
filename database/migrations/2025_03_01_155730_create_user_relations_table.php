<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_relations', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true)->primary();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();

            $table->unsignedBigInteger('related_with_user_id');


            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('related_with_user_id')->references('id')->on('users')->onDelete('cascade');


            $table->timestamps();
            $table->softDeletes();

            $table->unique(['creator_id', 'related_with_user_id'], 'user_relations_creator_related_unique');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('user_relations', function (Blueprint $table) {
            // Drop the unique constraint first
            $table->dropUnique('user_relations_creator_related_unique');
        });
        Schema::dropIfExists('user_relations');
    }
};
