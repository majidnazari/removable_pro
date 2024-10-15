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
        Schema::create('ip_trackings', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->unique()->index();
            $table->string('date_attemp')->nullable();
            $table->unsignedTinyInteger('today_attemp')->default(0);
            $table->unsignedBigInteger('total_attemp')->default(0);
            $table->enum('status', ["Active","Blocked", "Suspend", "None"])->default("Active");
            $table->string('expire_blocked_time')->nullable();
            $table->unsignedSmallInteger('number_of_blocked_times')->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_trackings');
    }
};
