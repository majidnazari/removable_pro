<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('creator_id'); // foreign key for the user
            // $table->unsignedBigInteger('editor_id')->nullable();

            //$table->string('name');
            $table->string('country_code',11);
            $table->string('mobile',11)->unique()->index();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean(column: 'mobile_is_veryfied')->default(false);

            $table->string('password')->nullable();
            $table->string(column: 'sent_code')->nullable();
            $table->string(column: 'code_expired_at')->nullable();
            $table->tinyInteger(column: 'today_attemp')->default(0);
            $table->string('date_attemp')->nullable();

            $table->unsignedBigInteger('total_attemp')->default(0);

            $table->string('expire_blocked_time')->nullable();
            $table->unsignedSmallInteger('number_of_blocked_times')->default(0);


            //$table->string(column: 'ip')->nullable();
            //$table->tinyInteger(column: 'ip_attemp')->default(0);
            //$table->string(column: 'ip_attemp_date')->nullable();


            $table->enum('status', ["Active", "Inactive", "Suspend","Blocked", "None"])->default("None");

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
