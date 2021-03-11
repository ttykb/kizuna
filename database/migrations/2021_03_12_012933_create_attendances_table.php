<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id()->comment('（主キー）ID');
            $table->date('base_date')->comment('基準日');
            $table->bigInteger('employee_id')->unsigned()->comment('従業員ID');
            $table->bigInteger('workplace_id')->unsigned()->comment('勤務場所ID');
            $table->boolean('is_pickup')->comment('送迎')->nullable();
            $table->time('overtime', $precision = 0)->comment('残業時間')->nullable();
            $table->boolean('is_daily_report')->comment('日報')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('no action');
            $table->foreign('workplace_id')->references('id')->on('workplaces')->onDelete('no action');

            $table->unique([
                'base_date',
                'employee_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
