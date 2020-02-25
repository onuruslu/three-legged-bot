<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeToLessonLinkedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);

            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['level_id']);

            $table->foreign('level_id')
                ->references('id')
                ->on('levels')
                ->onDelete('cascade');
        });

        Schema::table('lesson_pages', function (Blueprint $table) {
            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('cascade');
        });

        Schema::table('lesson_user', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);

            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['level_id']);

            $table->foreign('level_id')
                ->references('id')
                ->on('levels');
        });

        Schema::table('lesson_pages', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });

        Schema::table('lesson_user', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
                
            $table->foreign('lesson_id')
                ->references('id')
                ->on('lessons');
        });
    }
}
