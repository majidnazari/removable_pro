<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->index();
            $table->unsignedInteger('today_attempts')->default(0);
            $table->unsignedInteger('total_attempts')->default(0);
            $table->date('attempt_date')->nullable();
            $table->dateTime('expire_blocked_time')->nullable();
            $table->unsignedInteger('number_of_blocked_times')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_attempts');
    }
};
