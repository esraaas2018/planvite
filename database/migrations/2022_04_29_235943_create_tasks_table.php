<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->date('deadline');
            $table->foreignId('status_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('sprint_id')->constrained()->cascadeOnDelete();
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->boolean('deadline_notified')->default(false);
            $table->boolean('almost_deadline_notified')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('tasks');
    }
}
