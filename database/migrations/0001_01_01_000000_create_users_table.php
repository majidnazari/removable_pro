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
            
            $table->unsignedBigInteger('id', true)->primary(); 
          
            $table->string('country_code',6);
            $table->string('mobile',18)->unique()->index();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean(column: 'mobile_is_verified')->default(false);

            $table->string('password')->nullable();
            $table->string(column: 'sent_code')->nullable();
            $table->string(column: 'code_expired_at')->nullable();
            $table->integer('password_change_attempts')->default(0);
            $table->timestamp('last_password_change_attempt')->nullable();
            $table->tinyInteger(column: 'user_attempt_time')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->tinyInteger('status', )->default(0)->comment("-1=Blocked 0=None 1=Active 2=Inactive 3=Suspend 4=New");  
            $table->tinyInteger('role', )->default(3)->comment(" 1=Admin 2=Supporter 3=User");  
            $table->boolean('blood_user_relation_calculated')->default(false); 
            $table->string('clan_id')->nullable()->index();

          

           // $table->enum('role', ['admin', 'supporter', 'user'])->default('user');
 

            //$table->tinyInteger('blocked_attempts_count')->default(0);
            //$table->timestamp('blocked_until')->nullable();
            //$table->string('blocked_until')->nullable();
            //$table->string('status', 20)->default("None");
            //$table->string('status', ["Active", "Inactive", "Suspend","Blocked","New", "None"])->default("None");

            //$table->unique(['country_code', 'mobile'], 'unique_country_code_mobile');

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
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');

    }
};
