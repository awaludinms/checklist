<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->integer('checklist_id');
            $table->string('description');
            $table->boolean('is_completed')->default(0);
            $table->datetime('completed_at')->nullable();
            $table->string('due')->nullable();
            $table->integer('urgency')->nullable();
            $table->string('updated_by');
            $table->timestamps();
            $table->string('assignee_id')->nullable();
            $table->integer('task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
