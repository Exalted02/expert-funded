<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('challenges', function (Blueprint $table) {            
			$table->date('trade_date')->nullable()->after('status');
			$table->integer('trade_count')->nullable()->after('trade_date');
			$table->string('trade_pair')->nullable()->after('trade_count');
			$table->double('trade_result', 8, 2)->nullable()->after('trade_pair');
			$table->date('funded_date')->nullable()->after('trade_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            //
        });
    }
};
