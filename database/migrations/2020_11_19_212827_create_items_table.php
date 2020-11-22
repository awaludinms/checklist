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
            $table->datetime('due')->nullable();
            $table->integer('urgency')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->string('assignee_id')->nullable();
            $table->integer('task_id')->nullable();
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
