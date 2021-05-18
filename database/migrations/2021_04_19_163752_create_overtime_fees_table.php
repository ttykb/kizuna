<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_fees', function (Blueprint $table) {
            $table->id()->comment('（主キー）ID');
            $table->bigInteger('employee_id')->unsigned()->comment('従業員ID');
            $table->integer('price')->comment('金額');
            $table->date('app_start_date')->comment('適用開始日');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_fees');
    }
}
