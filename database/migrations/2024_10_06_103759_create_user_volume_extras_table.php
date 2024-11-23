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
        Schema::create('user_volume_extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('volume_extra_id');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('volume_extra_id')->references('id')->on('volume_extras')->onDelete('cascade');    
            $table->integer('remain_volume')->nullable()->comment("(MB)"); //this is just amount of subscriptions not extra

            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            //$table->enum('status',["Active","Inactive","None"])->default("Active");

            $table->tinyInteger('status', )->default(0)->comment("-1=Blocked 0=none  1=active 2=inactive 3=suspend ");   


            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_volume_extras');
    }
};
