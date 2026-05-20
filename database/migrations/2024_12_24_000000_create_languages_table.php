<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->tinyIncrements('language_id');
            $table->string('name', 50);
            $table->string('code', 5)->unique()->comment('ISO language code (e.g., en, kh, zh)');
            $table->string('native_name', 50)->nullable()->comment('Language name in its native script');
            $table->string('flag', 10)->nullable()->comment('Flag emoji or icon code');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('created_date')->nullable();
            $table->unsignedBigInteger('modify_by')->nullable();
            $table->date('modify_date')->nullable();
        });

        // Insert default languages
        DB::table('languages')->insert([
            [
                'name' => 'English',
                'code' => 'en',
                'native_name' => 'English',
                'flag' => '🇬🇧',
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
                'created_by' => 1,
                'created_date' => now()
            ],
            [
                'name' => 'Khmer',
                'code' => 'kh',
                'native_name' => 'ខ្មែរ',
                'flag' => '🇰🇭',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
                'created_by' => 1,
                'created_date' => now()
            ],
            [
                'name' => 'Chinese',
                'code' => 'zh',
                'native_name' => '中文',
                'flag' => '🇨🇳',
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 3,
                'created_by' => 1,
                'created_date' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
