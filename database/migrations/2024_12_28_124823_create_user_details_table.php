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
        Schema::create('user_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id');


            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('last_seen_family_board_id');

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('last_seen_family_board_id')->references('id')->on('family_boards')->onDelete('cascade');

            $table->string('mobile', 18)->index();
            $table->tinyInteger('status', )->default(2)->comment("1=Active 2=Inactive");

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
