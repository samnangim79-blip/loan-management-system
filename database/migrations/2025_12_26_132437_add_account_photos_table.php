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
        Schema::create('account_photos', function (Blueprint $table) {
            $table->bigIncrements('acct_photo_id');
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->string('file_name', 250)->nullable();
            $table->integer('photo_type')->default(0)->comment('0="account photo",1="document"');
            $table->date('date_added')->nullable();
            $table->integer('status')->default(1)->comment('1="active",0="inactive"');
            $table->string('remark', 100)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->index('acct_id');

            $table->foreign('acct_id')
                ->references('acct_id')
                ->on('account_infos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_photos');
    }
};
