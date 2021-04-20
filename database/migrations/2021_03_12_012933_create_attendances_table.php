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
            $table->bigInteger('worktype_id')->unsigned()->comment('勤務種別')->nullable();
            $table->bigInteger('workplace_id')->unsigned()->comment('勤務場所')->nullable();
            $table->integer('allowance')->comment('手当')->nullable();
            $table->boolean('pickup')->comment('送迎')->nullable();
            $table->time('overtime', $precision = 0)->comment('残業時間')->nullable();
            $table->boolean('is_daily_report')->comment('日報')->nullable();
            $table->boolean('is_daily_payment')->comment('日払い')->nullable();
            $table->string('note')->comment('備考')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

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
