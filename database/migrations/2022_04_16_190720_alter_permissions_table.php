<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->after('guard_name', function($table){
                $table->string('display_name')->nullable();
                $table->bigInteger('system_id')->unsigned()->nullable();
                $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');                          
            }); 
        });
    }

}
