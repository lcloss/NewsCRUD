<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatFieldsToArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('lead')->after('slug');
            $table->dateTime('published_at')->default(DB::raw('CURRENT_TIMESTAMP'))->after('date');
            $table->dateTime('expired_at')->nullable()->after('published_at');
            $table->integer('views_count')->default(0)->after('expired_at');
            $table->integer('comments_count')->default(0)->after('views');
            $table->boolean('top')->default(0)->after('comments');
            $table->boolean('recommended')->default(0)->after('top');
            $table->integer('facebook_shares')->default(0)->after('recommended');
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
            $table->dropColumn('lead');
            $table->dropColumn('published_at');
            $table->dropColumn('expired_at');
            $table->dropColumn('views_count');
            $table->dropColumn('comments_count');
            $table->dropColumn('top');
            $table->dropColumn('recommended');
            $table->dropColumn('facebook_shares');
            $table->dropColumn('twitter_shares');
            $table->dropColumn('linkedin_shares');
            $table->dropColumn('score');
        });
    }
}
