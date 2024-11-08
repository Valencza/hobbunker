<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('crash_reports', function (Blueprint $table) {
        $table->string('name_position')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('crash_reports', function (Blueprint $table) {
        $table->dropColumn('name_position');
    });
}

};
