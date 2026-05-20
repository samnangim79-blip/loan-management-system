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
        Schema::create('translation_keys', function (Blueprint $table) {
            $table->id('key_id');
            $table->string('key_name', 255)->unique()->comment('Translation key (e.g., common.general.save)');
            $table->string('group', 100)->index()->comment('Translation group/file (e.g., common, validation)');
            $table->text('en')->nullable()->comment('English translation');
            $table->text('kh')->nullable()->comment('Khmer translation');
            $table->text('zh')->nullable()->comment('Chinese translation');
            $table->text('description')->nullable()->comment('Description of what this key is used for');
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_translated')->default(false)->comment('Whether translations were auto-generated');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('created_date')->nullable();
            $table->unsignedBigInteger('modify_by')->nullable();
            $table->date('modify_date')->nullable();

            $table->index(['group', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_keys');
    }
};
