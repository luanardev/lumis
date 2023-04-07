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
        Schema::create('apps_installed', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('alias');
            $table->string('display_name');
            $table->string('group');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('background')->nullable();
            $table->integer('priority')->nullable();
            $table->enum('status', array('Enabled', 'Disabled'))->default('Enabled');
            $table->enum('hide', array('Yes', 'No'))->default('No');
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
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('apps_installed');
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
};
