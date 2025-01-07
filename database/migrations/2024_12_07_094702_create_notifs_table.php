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
        Schema::create('notifs', function (Blueprint $table) {

            $table->unsignedBigInteger('id', true)->primary(); 
            $table->unsignedBigInteger('creator_id')->index();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');// the user new created 

            // $table->unsignedBigInteger('user_id')->index(); 
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // the user that has cretor_id in his person mobile

            // Polymorphic columns
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type'); 
            
            $table->string('message', length: 255)->nullable();

            $table->tinyInteger('notif_status', )->default(2)->comment(" 1=Read 2=NotRead");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifs');
    }
};
