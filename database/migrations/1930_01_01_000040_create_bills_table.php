<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Счета к оплате
        Schema::create('bills', function (Blueprint $table) {
            $table->id();                       // Номер выставленного счёта
            $table->integer('resident_id');     // Ссылка на дачника
            $table->integer('period_id');       // Ссылка на период
            $table->float('amount_rub');        // Сумма к оплате

            // Дачнику за период выставляется только один счёт
            $table->unique(['resident_id', 'period_id']);
            $table->foreign('resident_id')              // Внешний ключ: нельзя удалять дачника
                  ->references('id')->on('residents');  // которому уже выставлен счёт
            $table->foreign('period_id')                // Внешний ключ: нельзя удалять период 
                  ->references('id')->on('periods');    // по которому уже выставлен счёт
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
