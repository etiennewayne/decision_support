<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username')->unique();
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('suffix', 20)->nullable();

            $table->string('email')->unique();
            $table->string('contact_no')->nullable();
            $table->string('role')->nullable();

            // $table->unsignedBigInteger('program_id');
            // $table->foreign('program_id')->references('program_id')->on('programs')
            //     ->onUpdate('cascade')
            //     ->onDelete('cascade');

            $table->string('sex', 20)->nullable();
            // $table->string('last_school_attended', 255)->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('street')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
