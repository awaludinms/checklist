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
            $table->string('update_by');
            $table->timestamps();
            $table->string('due')->nullable();
            $table->integer('urgency');
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
