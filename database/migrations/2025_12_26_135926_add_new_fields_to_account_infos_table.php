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
        Schema::table('account_infos', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->after('acct_type_id');
            $table->string('resident', 10)->nullable()->after('category');
            $table->string('currency_id', 10)->nullable()->after('resident');
            $table->decimal('extra_rate', 10, 2)->nullable()->after('currency_id');
            $table->text('remark')->nullable()->after('extra_rate');
            $table->unsignedBigInteger('modify_by')->nullable()->after('remark');
            $table->datetime('modify_date')->nullable()->after('modify_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_infos', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'resident',
                'currency_id',
                'extra_rate',
                'remark',
                'modify_by',
                'modify_date'
            ]);
        });
    }
};
