<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{

    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_type');
            $table->integer('ticket_request_type');
            $table->foreignId('status_id')->default(1)->constrained('ticket_statuses');
            $table->foreignId('user_id')->constrained('users'); // ko je uputio
            $table->foreignId('officer_id')->nullable()->constrained('users'); // ko je preuzeo
            $table->foreignId('HR_id')->nullable()->constrained('users'); // ko je finalno odobrio ili odbio zahtjev
            $table->foreignId('officer_approval')->nullable()->default(1)->constrained('request_statuses');
            $table->foreignId('HR_approval')->nullable()->default(1)->constrained('request_statuses');
            $table->timestamp('date_finished')->nullable();
            // $table->boolean('is_done')->default(false);
            $table->timestamps();
            // requesting office supplies
            $table->text('description_supplies')->nullable();
            $table->double('quantity')->nullable();
            // requesting equipment
            $table->foreignId('equipment_category_id')->nullable()->constrained('equipment_categories');
            $table->text('description_equipment')->nullable();
            // for reporting malfunctions
            $table->foreignId('equipment_id')->nullable()->constrained('equipment');
            $table->text('description_malfunction')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
