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
        Schema::create('user_merge_requests', function (Blueprint $table) {
            
            $table->unsignedBigInteger('id')->primary();

            $table->unsignedBigInteger('creator_id')->index(); 
            $table->unsignedBigInteger('editor_id')->nullable()->index(); 

            $table->unsignedBigInteger('user_sender_id')->index(); 
            $table->unsignedBigInteger('node_sender_id')->index(); 
            $table->unsignedBigInteger('user_receiver_id')->index(); 
            $table->unsignedBigInteger('node_receiver_id')->index(); 

            // Define foreign keys
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('user_sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('node_sender_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('user_receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('node_receiver_id')->references('id')->on('people')->onDelete('cascade');


            $table->tinyInteger('request_status_sender', )->default(3)->comment(" 1=Active 2=Cancel 3=Suspend");  
            $table->datetime(column: 'request_sender_expired_at')->nullable();
            $table->tinyInteger('request_status_receiver', )->default(3)->comment("1=Active 2=Refused 3=Suspend");   

            // $table->unsignedBigInteger(column: 'merge_user_sender_id')->nullable()->index();
            // $table->unsignedBigInteger('merge_user_receiver_id')->nullable()->index(); 
            // $table->foreign('merge_user_sender_id')->references('id')->on('people')->onDelete('cascade');
            // $table->foreign('merge_user_receiver_id')->references('id')->on('people')->onDelete('cascade'); 
            $table->string('merge_ids_sender')->nullable();  
            $table->string('merge_ids_receiver' )->nullable();  
            $table->tinyInteger('merge_status_sender', )->default(3)->comment(" 1=Active 2=Cancel 3=Suspend");  
            $table->datetime(column: 'merge_sender_expired_at')->nullable();
            $table->tinyInteger('merge_status_receiver', )->default(3)->comment(" 1=Active 2=Refused 3=Suspend"); 
            
            $table->tinyInteger('status', )->default(1)->comment(" 1=Active 2=Inactive 3=Suspend 4=Complete");   



            //$table->tinyInteger('merge_status', )->default(3)->comment("1=Active 2=Cansel 3=refused 4=Suspend");   

            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_merge_requests');
    }
};
