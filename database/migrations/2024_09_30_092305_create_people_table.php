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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->nullable();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade'); 

            $table->string('node_code')->unique()->index();
            //$table->string('family_code'); // like FA001 a code for each family node that can be used in Clan or other somewhere. and it is the same for me and my parrents and my children.
            $table->integer('node_level_x')->default(1)->index();
            $table->integer('node_level_y')->default(1)->index();
            //$table->string('naslan_id')->nullable()->unique();
            //$table->string('referal_code')->nullable()->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->datetime('birth_date')->nullable();
            $table->datetime('death_date')->nullable();
            //$table->string('mobile');
            $table->boolean('is_owner')->defalut(false);
            //$table->enum('gendar', ["Male", "Female", "None"])->default("None");   
            $table->smallInteger('gendar', )->default(1)->comment("1 is man 0 is woman");   

            //$table->string('family_title')->nullable();
            //$table->string('father_first_name')->nullable();
            //$table->string('mother_first_name')->nullable();
            //$table->string('mother_last_name')->nullable();



            $table->enum('status', ["Active", "Inactive", "Suspend", "None"])->default("None");

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
