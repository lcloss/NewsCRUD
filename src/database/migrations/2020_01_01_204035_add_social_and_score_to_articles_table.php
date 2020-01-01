<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialAndScoreToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->integer('facebook_shares')->default(0)->after('date');
            $table->integer('twitter_shares')->default(0)->after('facebook_shares');
            $table->integer('linkedin_shares')->default(0)->after('twitter_shares');
            $table->integer('score')->default(0)->after('linkedin_shares');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('facebook_shares');
            $table->dropColumn('twitter_shares');
            $table->dropColumn('linkedin_shares');
            $table->dropColumn('score');
        });
    }
}
