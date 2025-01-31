<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToStudioUsersTable extends Migration
{
    public function up()
    {
        Schema::table('StudioUsers', function (Blueprint $table) {
            $table->integer('studiokey')->after('userkey');
            $table->string('username')->after('studiokey');
            $table->integer('usertypekey')->after('username');
            $table->string('email')->unique()->after('usertypekey');
            $table->string('password')->after('email');
            $table->integer('rolekey')->after('password');
            $table->string('phonenumber')->after('rolekey');
            $table->string('address')->nullable()->after('phonenumber');
            $table->boolean('isactive')->after('address');
            $table->string('profileimage')->nullable()->after('isactive');
        });
    }
    

    public function down()
    {
        Schema::table('StudioUsers', function (Blueprint $table) {
            $table->dropColumn([
                'studiokey',
                'username',
                'usertypekey',
                'email',
                'password',
                'rolekey',
                'phonenumber',
                'address',
                'isactive',
                'profileimage',
            ]);
        });
    }
}