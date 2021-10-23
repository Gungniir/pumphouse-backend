<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Периоды (месяцы)
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('begin_date'); // Начало периода (1 число месяца 00:00:00)
            $table->timestampTz('end_date');   // Окончание периода (последнее число месяца 23:59:59)
        });
        // Если при вводе показаний счетчика, в таблице periods нет записи на соответствующий месяц,
        // то надо её создать так, чтобы begin_date была началом месяца, а end_date - его окончанием
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
