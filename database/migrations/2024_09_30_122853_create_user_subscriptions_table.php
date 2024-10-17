<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('creator_id');
            // $table->unsignedBigInteger('editor_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscription_id');


            //$table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');              
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->integer('remain_volume')->nullable()->comment("(MB)"); //this is just amount of subscriptions not extra

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
        Schema::dropIfExists('user_subscriptions');
    }
};
