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
            $table->id();
            $table->unsignedBigInteger('sender_id')->index(); // it is the is_owner =1 that a person send request to another node
            $table->unsignedBigInteger('reciver_id')->index(); // foreign key for the spouse
            // Define foreign keys
            $table->foreign('sender_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('reciver_id')->references('id')->on('people')->onDelete('cascade');
            $table->boolean('request_is_read', )->default(0)->comment(" 0=not read 1=read");  
            $table->datetime(column: 'request_expired_at')->nullable();
            $table->tinyInteger('request_status', )->default(3)->comment(" 1=active 2=refused 3=susspend");   



            $table->unsignedBigInteger(column: 'merge_sender_id')->nullable()->index(); // it is the is_owner =1 that a person send request to another node
            $table->unsignedBigInteger('merge_reciver_id')->nullable()->index(); // foreign key for the spouse
            $table->foreign('merge_sender_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('merge_reciver_id')->references('id')->on('people')->onDelete('cascade'); 
            $table->boolean('merge_is_read', )->default(0)->comment(" 0=not read 1=read");  
            $table->datetime(column: 'merge_expired_at')->nullable();
            $table->tinyInteger('merge_status', )->default(3)->comment(" 1=active 2=refused 3=susspend");   

            
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
