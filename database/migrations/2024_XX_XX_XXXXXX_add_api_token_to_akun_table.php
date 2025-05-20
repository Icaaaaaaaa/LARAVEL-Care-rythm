<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiTokenToAkunTable extends Migration
{
    public function up()
    {
        Schema::table('akun', function (Blueprint $table) {
            $table->string('api_token', 100)->nullable()->after('role');
        });
    }

    public function down()
    {
        Schema::table('akun', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
}
