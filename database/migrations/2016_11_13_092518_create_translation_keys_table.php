<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translation_keys', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_lang_id')->unsigned();
            $table->foreign('project_lang_id')->references('id')->on('project_langs')->onDelete('cascade');

            $table->integer('project_namespace_id')->unsigned();
            $table->foreign('project_namespace_id')->references('id')->on('project_namespaces')->onDelete('cascade');

            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->string('translation_key');

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
        Schema::dropIfExists('translation_keys');
    }
}
