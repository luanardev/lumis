<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('password', function($table){
                $table->uuid('staff_id')->nullable();
                $table->uuid('campus_id')->nullable();
                $table->enum('status', array('Active', 'Inactive'))->default('Active');
            });
        });
    }

};
