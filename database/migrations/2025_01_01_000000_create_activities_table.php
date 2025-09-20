<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('user');
            $table->nullableMorphs('subject');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();
            $table->timestamps();

            $table->index(['name']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
