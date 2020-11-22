<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('object_domain');
            $table->string('object_id');
            $table->string('description');
            $table->boolean('is_completed')->default(0);
            $table->datetime('completed_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->datetime('due')->nullable();
            $table->integer('urgency');
            $table->integer('task_id')->nullable();
            $table->integer('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists');
    }
}
