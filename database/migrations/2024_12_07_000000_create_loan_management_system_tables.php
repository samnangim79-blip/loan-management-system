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
        // Disable foreign key checks for migration
        Schema::disableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('phone_no')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->dateTime('last_login')->nullable();
            $table->string('avartar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Create access_profiles table
        Schema::create('access_profiles', function (Blueprint $table) {
            $table->tinyIncrements('profile_id');
            $table->string('profile', 50)->nullable();
            $table->decimal('deposit_limit', 20, 5)->nullable();
            $table->decimal('withdrawal_limit', 20, 5)->nullable();
            $table->decimal('loan_limit', 20, 5)->nullable();
            $table->decimal('non_cash_limit', 20, 5)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create access_profile_details table
        Schema::create('access_profile_details', function (Blueprint $table) {
            $table->increments('profile_detail_id');
            $table->unsignedTinyInteger('profile_id')->nullable();
            $table->unsignedInteger('module_id')->nullable();

            $table->index('module_id');
            $table->index('profile_id');

            $table->foreign('profile_id')
                ->references('profile_id')
                ->on('access_profiles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create countries table (required for dependencies)
        Schema::create('countries', function (Blueprint $table) {
            $table->smallIncrements('country_id');
            $table->string('country', 255);
            $table->string('country_kh', 50)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create provinces table
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('name_kh', 100)->nullable();
            $table->string('name_en', 100)->nullable();
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->timestamps();
            $table->index('country_id');

            $table->foreign('country_id')
                ->references('country_id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create districts table
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('name_kh', 100)->nullable();
            $table->string('name_en', 100)->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->timestamps();

            $table->index('province_id');

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create communes table
        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('name_kh', 100)->nullable();
            $table->string('name_en', 100)->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->timestamps();

            $table->index('district_id');

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create villages table
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('name_kh', 100)->nullable();
            $table->string('name_en', 100)->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->timestamps();

            $table->index('commune_id');

            $table->foreign('commune_id')
                ->references('id')
                ->on('communes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create branchs table
        Schema::create('branchs', function (Blueprint $table) {
            $table->unsignedSmallInteger('branch_id')->primary();
            $table->string('branch_name', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create staffs table
        Schema::create('staffs', function (Blueprint $table) {
            $table->increments('staff_id');
            $table->string('ic_no', 30);
            $table->string('full_name', 50)->nullable();
            $table->char('gender', 1)->nullable();
            $table->date('dob')->nullable();
            $table->string('pob', 100)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('position', 30)->nullable();
            $table->unsignedSmallInteger('branch_id')->nullable();

            $table->index('branch_id');

            $table->foreign('branch_id')
                ->references('branch_id')
                ->on('branchs')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create user_logins table
        Schema::create('user_logins', function (Blueprint $table) {
            $table->smallIncrements('user_id');
            $table->unsignedInteger('staff_id')->nullable();
            $table->string('login_name', 50)->nullable();
            $table->string('password', 50)->nullable();
            $table->date('next_pwd_expire')->nullable();
            $table->tinyInteger('failed_log')->default(0)->comment('Max failed = 5');
            $table->string('log_ip', 50)->default('')->comment('Store the IP of current loged in PC');
            $table->tinyInteger('status')->default(0)->comment('0=Active, 1=Suspended, 2=Deleted');
            $table->decimal('sys_cash_limit', 20, 5)->nullable();
            $table->unsignedTinyInteger('profile_id')->nullable();
            $table->unsignedSmallInteger('branch_id')->nullable();

            $table->index('staff_id');
            $table->index('profile_id');
            $table->index('branch_id');

            $table->foreign('profile_id')
                ->references('profile_id')
                ->on('access_profiles')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('branch_id')
                ->references('branch_id')
                ->on('branchs')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('staff_id')
                ->references('staff_id')
                ->on('staffs')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create customer_infos table
        Schema::create('customer_infos', function (Blueprint $table) {
            $table->bigIncrements('cust_id');
            $table->string('id_no', 30);
            $table->string('name_en', 50)->nullable();
            $table->string('name_kh', 50)->nullable();
            $table->string('gender', 50)->nullable();
            $table->integer('marital_status')->nullable()->comment('0="Single",1="Married"');
            $table->date('dob')->nullable();
            $table->string('pob', 100)->nullable();
            $table->string('phone1', 50)->nullable();
            $table->string('phone2', 50)->nullable();
            $table->string('phone3', 50)->nullable();
            $table->string('address', 100)->default('');
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->string('occupation', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('spouse_id_no', 30)->nullable();
            $table->string('spouse_name_en', 50)->nullable();
            $table->string('spouse_name_kh', 50)->nullable();
            $table->date('spouse_dob')->nullable();
            $table->string('guarantor_id_no', 30)->nullable();
            $table->string('guarantor_name_en', 50)->nullable();
            $table->string('guarantor_name_kh', 50)->nullable();
            $table->string('family_book', 150)->nullable();
            $table->date('guarantor_dob')->nullable();
            $table->unsignedInteger('staff_id')->nullable();
            $table->text('remark')->nullable();
            $table->unsignedSmallInteger('created_by')->nullable();
            $table->date('created_date')->nullable();
            $table->unsignedSmallInteger('modify_by')->nullable();
            $table->date('modify_date')->nullable();
            $table->integer('nationality_id')->nullable();

            $table->index('country_id');
            $table->index('province_id');
            $table->index('district_id');
            $table->index('commune_id');
            $table->index('village_id');
            $table->index('staff_id');
            $table->index('created_by');
            $table->index('modify_by');

            $table->foreign('country_id')
                ->references('country_id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('commune_id')
                ->references('id')
                ->on('communes')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('staff_id')
                ->references('staff_id')
                ->on('staffs')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('created_by')
                ->references('user_id')
                ->on('user_logins')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('modify_by')
                ->references('user_id')
                ->on('user_logins')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create currencys table
        Schema::create('currencys', function (Blueprint $table) {
            $table->tinyIncrements('ccy_id');
            $table->char('currency', 4);
            $table->decimal('ccy_rate', 15, 5)->default(0.00000)->comment('To USD rate. ex: (KHR=4000) =USD1');
            $table->smallInteger('round_value')->default(0)->comment('if decimal place =0, round_value must be >=1. else =0');
            $table->tinyInteger('decimal_place')->default(2)->comment('Number of digit for decimal rounding');
            $table->tinyInteger('compare_value')->default(0);
            $table->tinyInteger('value_format')->default(0);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create gl_l1s table
        Schema::create('gl_l1s', function (Blueprint $table) {
            $table->tinyInteger('l1_id')->primary();
            $table->string('l1_desc', 250)->nullable();
            $table->char('drcr', 2)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create gl_l2s table
        Schema::create('gl_l2s', function (Blueprint $table) {
            $table->tinyInteger('l2_id')->primary();
            $table->string('l2_desc', 250)->nullable();
            $table->tinyInteger('l1_id')->nullable();

            $table->index('l1_id');

            $table->foreign('l1_id')
                ->references('l1_id')
                ->on('gl_l1s')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create gl_l3s table
        Schema::create('gl_l3s', function (Blueprint $table) {
            $table->smallInteger('l3_id')->primary();
            $table->string('l3_desc', 250)->nullable();
            $table->tinyInteger('l2_id')->nullable();

            $table->index('l2_id');

            $table->foreign('l2_id')
                ->references('l2_id')
                ->on('gl_l2s')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create gl_l4s table
        Schema::create('gl_l4s', function (Blueprint $table) {
            $table->smallInteger('l4_id')->primary();
            $table->string('l4_desc', 250)->nullable();
            $table->smallInteger('l3_id')->nullable();

            $table->index('l3_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create gls table
        Schema::create('gls', function (Blueprint $table) {
            $table->increments('gl_id');
            $table->string('gl_code', 11);
            $table->string('gl_name', 80)->nullable();
            $table->string('gl_name_kh', 80)->nullable();
            $table->smallInteger('l4_id')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create account_types table
        Schema::create('account_types', function (Blueprint $table) {
            $table->tinyIncrements('acct_type_id');
            $table->string('acct_type', 50)->nullable();
            $table->unsignedTinyInteger('ccy_id')->nullable();
            $table->tinyInteger('resident')->nullable()->comment('0-RESIDENT, 1-NON-RESIDENT');
            $table->decimal('withhold_tax', 5, 2)->default(0.00);
            $table->unsignedInteger('gl_id')->nullable();
            $table->unsignedInteger('withhold_gl')->nullable();
            $table->unsignedInteger('accrued_int_gl')->nullable();
            $table->integer('interest_gl')->nullable();
            $table->integer('category')->nullable()->comment('0=deposit,1=term deposit,2=loan');

            $table->index('ccy_id');
            $table->index('gl_id');
            $table->index('withhold_gl');
            $table->index('accrued_int_gl');

            $table->foreign('ccy_id')
                ->references('ccy_id')
                ->on('currencys')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('gl_id')
                ->references('gl_id')
                ->on('gls')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('withhold_gl')
                ->references('gl_id')
                ->on('gls')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('accrued_int_gl')
                ->references('gl_id')
                ->on('gls')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create account_infos table
        Schema::create('account_infos', function (Blueprint $table) {
            $table->bigIncrements('acct_id');
            $table->unsignedBigInteger('cust_id')->nullable();
            $table->string('acct_name', 50)->nullable();
            $table->string('acct_no', 7)->nullable();
            $table->unsignedTinyInteger('acct_type_id')->nullable();
            $table->integer('joint_flag')->nullable();
            $table->string('mandatory', 50)->nullable();
            $table->tinyInteger('account_status')->nullable()->comment('ACTIVE, DORMANT, SUSPENDED, CLOSED');
            $table->integer('branch_id')->nullable();
            $table->date('opened_date')->nullable();
            $table->unsignedSmallInteger('opened_by')->nullable();
            $table->date('last_withdraw_date')->nullable();
            $table->date('close_date')->nullable();
            $table->integer('close_by')->nullable();

            $table->index('cust_id');
            $table->index('acct_type_id');
            $table->index('opened_by');

            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customer_infos')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('acct_type_id')
                ->references('acct_type_id')
                ->on('account_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('opened_by')
                ->references('user_id')
                ->on('user_logins')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create accrued_ints table
        Schema::create('accrued_ints', function (Blueprint $table) {
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->decimal('last_accrued_int', 20, 5)->nullable();
            $table->date('last_accrued_date')->nullable();
            $table->decimal('accrued_int_balance', 20, 5)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create asset_types table
        Schema::create('asset_types', function (Blueprint $table) {
            $table->tinyIncrements('asset_type_id');
            $table->string('asset_type', 200)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create branch_trans table
        Schema::create('branch_trans', function (Blueprint $table) {
            $table->increments('branch_tran_id');
            $table->integer('branch_id')->nullable();
            $table->date('tran_date')->nullable();
            $table->integer('started_by')->nullable();
            $table->dateTime('started_date')->nullable();
            $table->integer('ended_by')->nullable();
            $table->dateTime('ended_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create cash_mgts table
        Schema::create('cash_mgts', function (Blueprint $table) {
            $table->increments('cash_mgt_id');
            $table->date('tran_date')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->char('in_out', 1)->nullable()->comment('i = in , o=out');
            $table->decimal('balance', 20, 5)->nullable();
            $table->unsignedTinyInteger('ccy_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('date_done')->nullable();
            $table->string('remark', 50)->nullable();

            $table->index('ccy_id');

            $table->foreign('ccy_id')
                ->references('ccy_id')
                ->on('currencys')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all remaining simple tables
        Schema::create('cheque_clears', function (Blueprint $table) {
            $table->increments('chq_clear_id');
            $table->string('chq_no', 255)->nullable();
            $table->integer('tran_id')->nullable();
            $table->integer('clear_by')->nullable();
            $table->dateTime('clear_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cheque_issues', function (Blueprint $table) {
            $table->increments('chq_issue_id');
            $table->integer('acct_id')->nullable();
            $table->string('chq_no', 255)->nullable();
            $table->string('chq_from_no', 255)->nullable();
            $table->string('chq_to_no', 255)->nullable();
            $table->integer('status')->nullable();
            $table->unsignedInteger('issue_by')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cheque_maintenances', function (Blueprint $table) {
            $table->increments('chq_id');
            $table->date('tran_date')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('qty')->nullable();
            $table->string('chq_from_no', 255)->nullable();
            $table->string('chq_to_no', 255)->nullable();
            $table->unsignedInteger('main_by')->nullable();
            $table->dateTime('main_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cheque_stops', function (Blueprint $table) {
            $table->increments('chq_stop_id');
            $table->string('chq_no', 255)->nullable();
            $table->text('reason')->nullable();
            $table->text('note')->nullable();
            $table->integer('stopped_by')->nullable();
            $table->dateTime('stopped_date')->nullable();
            $table->integer('released_by')->nullable();
            $table->dateTime('released_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collateral_types', function (Blueprint $table) {
            $table->smallIncrements('collateral_type_id');
            $table->string('collateral_type', 50)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('payment_frequencys', function (Blueprint $table) {
            $table->smallIncrements('frequency_id');
            $table->string('frequency', 20)->nullable();
            $table->smallInteger('num_days')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_schedules', function (Blueprint $table) {
            $table->increments('loan_schedule_id');
            $table->string('contract_no', 15)->nullable();
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->date('date_issue')->nullable();
            $table->unsignedSmallInteger('frequency_id')->nullable();
            $table->date('last_pay_date')->nullable();
            $table->date('next_pay_date')->nullable();
            $table->integer('tenor')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->decimal('os_balance', 20, 5)->nullable();
            $table->decimal('int_rate', 5, 2)->nullable();
            $table->decimal('extra_rate', 5, 2)->nullable();
            $table->tinyInteger('interest_mode')->nullable();
            $table->tinyInteger('payment_mode')->nullable();
            $table->decimal('savings', 20, 5)->nullable();
            $table->unsignedBigInteger('credit_to_acct')->nullable();
            $table->unsignedBigInteger('auto_pay_from_acct')->nullable();
            $table->unsignedSmallInteger('user_id')->nullable();
            $table->date('approved_date')->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->tinyInteger('purpose_id')->nullable();
            $table->text('remark')->nullable();
            $table->date('end_pay_date')->nullable();
            $table->date('next_date')->nullable();
            $table->unsignedInteger('gl_credit')->nullable();

            $table->index('acct_id');
            $table->index('frequency_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_schedule_tmps', function (Blueprint $table) {
            $table->increments('tmp_loan_schedule_id');
            $table->string('contract_no', 15)->nullable();
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->date('date_issue')->nullable();
            $table->unsignedSmallInteger('frequency_id')->nullable();
            $table->date('next_pay_date')->nullable();
            $table->integer('tenor')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->decimal('os_balance', 20, 5)->nullable();
            $table->decimal('int_rate', 5, 2)->nullable();
            $table->decimal('extra_rate', 5, 2)->nullable();
            $table->tinyInteger('interest_mode')->nullable();
            $table->tinyInteger('payment_mode')->nullable();
            $table->decimal('savings', 20, 5)->nullable();
            $table->unsignedBigInteger('credit_to_acct')->nullable();
            $table->unsignedBigInteger('auto_pay_from_acct')->nullable();
            $table->unsignedSmallInteger('user_id')->nullable();
            $table->date('approved_date')->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->tinyInteger('purpose_id')->nullable();
            $table->text('remark')->nullable();
            $table->date('end_pay_date')->nullable();
            $table->date('next_date')->nullable();
            $table->unsignedInteger('gl_credit')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collaterals', function (Blueprint $table) {
            $table->increments('collateral_id');
            $table->unsignedInteger('loan_schedule_id')->nullable();
            $table->unsignedSmallInteger('collateral_type_id')->nullable();
            $table->integer('collateral_value')->nullable();
            $table->string('collateral_no', 50)->nullable();
            $table->date('date_issue')->nullable();
            $table->string('remarks', 255)->nullable();

            $table->index('collateral_type_id');
            $table->index('loan_schedule_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collateral_tmps', function (Blueprint $table) {
            $table->increments('tmp_collateral_id');
            $table->unsignedInteger('loan_schedule_id')->nullable();
            $table->unsignedSmallInteger('collateral_type_id')->nullable();
            $table->integer('collateral_value')->nullable();
            $table->string('collateral_no', 50)->nullable();
            $table->date('date_issue')->nullable();
            $table->string('remarks', 255)->nullable();

            $table->index('collateral_type_id');
            $table->index('loan_schedule_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other tables that don't have foreign key dependencies
        Schema::create('configs', function (Blueprint $table) {
            $table->smallInteger('config_id')->primary();
            $table->string('config_name', 255)->nullable();
            $table->string('config_value', 255)->nullable();
            $table->string('remark', 255)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('gl_maps', function (Blueprint $table) {
            $table->increments('gl_map_id');
            $table->string('short_code', 5)->nullable();
            $table->string('tran_desc', 200)->nullable();
            $table->unsignedInteger('debit_gl_id')->nullable();
            $table->unsignedInteger('credit_gl_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('trans', function (Blueprint $table) {
            $table->bigIncrements('tran_id');
            $table->unsignedSmallInteger('branch_id');
            $table->date('tran_date');
            $table->unsignedInteger('gl_map_id')->nullable();
            $table->decimal('amount', 20, 5)->default(0.00000);
            $table->unsignedTinyInteger('ccy_id');
            $table->text('discription')->nullable();
            $table->unsignedSmallInteger('user_id')->nullable();
            $table->dateTime('done_date')->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->smallInteger('tran_type')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('tran_tmps', function (Blueprint $table) {
            $table->bigIncrements('tmp_tran_id');
            $table->unsignedSmallInteger('branch_id');
            $table->date('tran_date');
            $table->unsignedInteger('gl_map_id')->nullable();
            $table->decimal('amount', 20, 5)->default(0.00000);
            $table->unsignedTinyInteger('ccy_id');
            $table->text('discription')->nullable();
            $table->unsignedSmallInteger('user_id')->nullable();
            $table->dateTime('done_date')->nullable();
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->integer('is_approve')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('tran_details', function (Blueprint $table) {
            $table->bigIncrements('tran_detail_id');
            $table->unsignedBigInteger('tran_id')->nullable();
            $table->char('dr_cr', 1)->nullable();
            $table->unsignedInteger('gl_id')->nullable();
            $table->decimal('balance', 20, 5)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('tran_detail_tmps', function (Blueprint $table) {
            $table->bigIncrements('tmp_tran_detail_id');
            $table->unsignedBigInteger('tran_id')->nullable();
            $table->char('dr_cr', 1)->nullable();
            $table->unsignedInteger('gl_id')->nullable();
            $table->decimal('balance', 20, 5)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all remaining tables
        Schema::create('cust_income_historys', function (Blueprint $table) {
            $table->bigIncrements('cust_income_id');
            $table->unsignedBigInteger('cust_id')->nullable();
            $table->decimal('income', 20, 5)->nullable();
            $table->decimal('expense', 20, 5)->nullable();
            $table->decimal('liability', 20, 5)->nullable();
            $table->string('remark', 50)->nullable();
            $table->dateTime('posted_date')->nullable();
            $table->unsignedInteger('posted_by')->nullable();
            $table->date('last_updated')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->index('cust_id');

            $table->foreign('cust_id')
                ->references('cust_id')
                ->on('customer_infos')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cust_asset_details', function (Blueprint $table) {
            $table->bigIncrements('cust_asset_id');
            $table->unsignedBigInteger('cust_income_id')->nullable();
            $table->unsignedTinyInteger('asset_type_id')->nullable();
            $table->string('description', 100)->nullable();
            $table->decimal('estimated_value', 20, 5)->nullable();

            $table->index('cust_income_id');
            $table->index('asset_type_id');

            $table->foreign('cust_income_id')
                ->references('cust_income_id')
                ->on('cust_income_historys')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('asset_type_id')
                ->references('asset_type_id')
                ->on('asset_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create remaining tables without foreign keys
        Schema::create('cheque_cleareds', function (Blueprint $table) {
            $table->unsignedBigInteger('chq_id')->nullable();
            $table->unsignedBigInteger('tran_id')->nullable();
            $table->tinyInteger('chq_status_id')->nullable();

            $table->index('chq_status_id');
            $table->index('tran_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cheque_statuss', function (Blueprint $table) {
            $table->tinyInteger('chq_status_id')->nullable();
            $table->string('chq_status', 50)->nullable();
            $table->tinyInteger('re_presenting')->nullable();

            $table->index('chq_status_id');

            $table->foreign('chq_status_id')
                ->references('chq_status_id')
                ->on('cheque_cleareds')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cheque_issueds', function (Blueprint $table) {
            $table->bigInteger('chq_issued_id')->nullable();
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->integer('chq_no_from')->nullable();
            $table->integer('chq_no_to')->nullable();
            $table->dateTime('issued_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other remaining tables
        Schema::create('collateral_details', function (Blueprint $table) {
            $table->increments('loan_col_detail_id');
            $table->unsignedInteger('collateral_id')->nullable();
            $table->unsignedInteger('col_detail_id')->nullable();
            $table->text('col_value')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collateral_detail_tmps', function (Blueprint $table) {
            $table->increments('tmp_loan_col_detail_id');
            $table->unsignedInteger('collateral_id')->nullable();
            $table->unsignedInteger('col_detail_id')->nullable();
            $table->text('col_value')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collateral_releases', function (Blueprint $table) {
            $table->increments('release_id');
            $table->date('tran_date')->nullable();
            $table->unsignedInteger('collateral_id')->nullable();
            $table->unsignedInteger('release_by')->nullable();
            $table->date('release_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->date('approved_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collateral_release_tmps', function (Blueprint $table) {
            $table->increments('tmp_release_id');
            $table->date('tran_date')->nullable();
            $table->unsignedInteger('collateral_id')->nullable();
            $table->unsignedInteger('release_by')->nullable();
            $table->dateTime('release_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('collateral_type_details', function (Blueprint $table) {
            $table->increments('collateral_detail_id');
            $table->unsignedSmallInteger('collateral_type_id')->nullable();
            $table->string('description', 255)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other tables
        Schema::create('cust_acct_holds', function (Blueprint $table) {
            $table->bigInteger('acct_hold_id')->nullable();
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->decimal('hold_amount', 20, 5)->nullable();
            $table->string('description', 250)->nullable();
            $table->dateTime('hold_date')->nullable();
            $table->string('hold_by', 50)->nullable();

            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cust_acct_trans', function (Blueprint $table) {
            $table->bigIncrements('cust_tran_id');
            $table->unsignedBigInteger('tran_id');
            $table->unsignedBigInteger('acc_id')->nullable();
            $table->decimal('amt', 20, 5);
            $table->char('dr_cr', 1);
            $table->decimal('os_bal', 20, 5);
            $table->tinyInteger('passbook_flag')->nullable();

            $table->index('acc_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cust_acct_tran_tmps', function (Blueprint $table) {
            $table->bigIncrements('cust_tran_id');
            $table->unsignedBigInteger('tran_id');
            $table->unsignedBigInteger('acc_id')->nullable();
            $table->decimal('amt', 20, 5);
            $table->char('dr_cr', 1);
            $table->decimal('os_bal', 20, 5);
            $table->tinyInteger('passbook_flag')->nullable();

            $table->index('acc_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cust_photos', function (Blueprint $table) {
            $table->bigIncrements('cust_photo_id');
            $table->unsignedBigInteger('cust_id')->nullable();
            $table->string('file_name', 250)->nullable();
            $table->integer('photo_type')->nullable()->comment('0="customer photo",1="account photo"');
            $table->date('date_added')->nullable();
            $table->integer('status')->nullable()->comment('0="active",1="inactive"');
            $table->string('remark', 100)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->index('cust_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('cust_unclears', function (Blueprint $table) {
            $table->bigInteger('acct_unclear_id')->nullable();
            $table->unsignedBigInteger('cust_tran_id')->nullable();
            $table->string('description', 250)->nullable();
            $table->string('clear_by', 50)->nullable();
            $table->dateTime('clear_date')->nullable();

            $table->index('cust_tran_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other remaining tables
        Schema::create('ex_rate_historys', function (Blueprint $table) {
            $table->bigInteger('ex_rate_history_id')->nullable();
            $table->decimal('ex_rate', 5, 2)->nullable();
            $table->date('rate_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fd_options', function (Blueprint $table) {
            $table->tinyInteger('fd_option_id')->primary();
            $table->string('fd_option', 50)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fd_terms', function (Blueprint $table) {
            $table->unsignedInteger('fd_term_id')->primary();
            $table->string('term_name', 50)->nullable();
            $table->integer('days_num')->nullable();
            $table->decimal('int_rate', 5, 2)->nullable();
            $table->integer('grace_period')->nullable();
            $table->decimal('break_term_fee', 10, 2)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fd_certs', function (Blueprint $table) {
            $table->bigInteger('fd_cert_id')->primary();
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->date('date_issue')->nullable();
            $table->date('matured_date')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->decimal('int_rate', 5, 2)->nullable();
            $table->decimal('extra_rate', 5, 2)->nullable();
            $table->tinyInteger('fd_option_id')->nullable();
            $table->unsignedInteger('fd_term_id')->nullable();
            $table->string('acct_for_int', 50)->nullable();
            $table->string('acct_for_prin', 50)->nullable();
            $table->date('future_dep_date')->nullable();
            $table->string('done_by', 50)->nullable();

            $table->index('acct_id');
            $table->index('fd_term_id');
            $table->index('fd_option_id');

            $table->foreign('fd_option_id')
                ->references('fd_option_id')
                ->on('fd_options')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('fd_term_id')
                ->references('fd_term_id')
                ->on('fd_terms')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fd_future_deps', function (Blueprint $table) {
            $table->integer('fd_dep_id')->nullable();
            $table->bigInteger('fd_cert_id')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->date('date_to_dep')->nullable();
            $table->dateTime('date_done')->nullable();

            $table->index('fd_cert_id');

            $table->foreign('fd_cert_id')
                ->references('fd_cert_id')
                ->on('fd_certs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fd_roll_overs', function (Blueprint $table) {
            $table->integer('roll_over_id')->nullable();
            $table->bigInteger('fd_cert_id')->nullable();
            $table->date('roll_over_date')->nullable();
            $table->date('matured_date')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->decimal('int_rate', 5, 2)->nullable();

            $table->index('fd_cert_id');

            $table->foreign('fd_cert_id')
                ->references('fd_cert_id')
                ->on('fd_certs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fd_trans', function (Blueprint $table) {
            $table->integer('fd_tran_id')->primary();
            $table->bigInteger('fd_cert_id')->nullable();
            $table->string('status', 50)->nullable();

            $table->index('fd_cert_id');

            $table->foreign('fd_cert_id')
                ->references('fd_cert_id')
                ->on('fd_certs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other remaining tables
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->increments('fa_id');
            $table->string('fa_code', 50)->nullable();
            $table->string('fa_desc', 250)->nullable();
            $table->text('fa_comment')->nullable();
            $table->tinyInteger('fa_type_id')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 20, 5)->nullable();
            $table->unsignedTinyInteger('ccy_id')->nullable();
            $table->integer('usefull_life')->nullable();
            $table->decimal('net_value', 20, 5)->nullable();
            $table->date('dispose_date')->nullable();
            $table->decimal('dispose_value', 20, 5)->nullable();
            $table->text('dispose_comment')->nullable();
            $table->unsignedInteger('added_by')->nullable();
            $table->dateTime('added_date')->nullable();
            $table->unsignedInteger('dispose_by')->nullable();
            $table->unsignedInteger('credit_gl')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fixed_asset_depres', function (Blueprint $table) {
            $table->increments('depre_id');
            $table->date('depre_date')->nullable();
            $table->unsignedBigInteger('tran_id')->nullable();
            $table->unsignedInteger('fa_id')->nullable();
            $table->decimal('amount', 20, 5)->nullable();

            $table->index('fa_id');
            $table->index('tran_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('fixed_asset_types', function (Blueprint $table) {
            $table->tinyIncrements('fa_type_id');
            $table->string('fa_type', 50)->nullable();
            $table->unsignedInteger('gl_id')->nullable();
            $table->unsignedInteger('depre_gl')->nullable();
            $table->unsignedInteger('exp_gl')->nullable();
            $table->unsignedInteger('dispose_gl')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all group related tables
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('group_id');
            $table->string('group_name', 50)->nullable();
            $table->date('date_issue')->nullable();
            $table->unsignedSmallInteger('added_by')->nullable();
            $table->date('added_date')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->date('updated_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('group_details', function (Blueprint $table) {
            $table->bigIncrements('group_detail_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('contract_no', 15)->nullable();

            $table->index('group_id');
            $table->index('contract_no');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('group_detail_tmps', function (Blueprint $table) {
            $table->bigIncrements('group_detail_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('contract_no', 15)->nullable();

            $table->index('group_id');
            $table->index('contract_no');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('group_tmps', function (Blueprint $table) {
            $table->bigIncrements('group_id');
            $table->string('group_name', 50)->nullable();
            $table->date('date_issue')->nullable();
            $table->unsignedSmallInteger('added_by')->nullable();
            $table->date('added_date')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->date('updated_date')->nullable();
            $table->integer('is_approve')->default(0);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other remaining tables
        Schema::create('int_rates', function (Blueprint $table) {
            $table->bigIncrements('int_rate_id');
            $table->double('rate', 5, 2)->nullable();
            $table->unsignedTinyInteger('acct_type_id')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('joint_account_holders', function (Blueprint $table) {
            $table->unsignedBigInteger('acct_id');
            $table->unsignedBigInteger('cust_id');
            $table->date('joint_date')->nullable();
            $table->unsignedSmallInteger('joint_added_by')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0-ACTIVE 1-DELETED');
            $table->dateTime('updated_date')->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();

            $table->primary(['acct_id', 'cust_id']);
            $table->index('cust_id');
            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all loan related tables
        Schema::create('loan_arears', function (Blueprint $table) {
            $table->increments('arrear_id');
            $table->unsignedInteger('loan_schedule_id')->nullable();
            $table->decimal('arrear_int', 20, 5)->nullable();
            $table->decimal('arrear_prin', 20, 5)->nullable();
            $table->decimal('arear_penalty', 20, 5)->nullable();
            $table->decimal('arear_saving', 20, 5)->nullable();
            $table->date('arrear_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_arrear_details', function (Blueprint $table) {
            $table->unsignedInteger('arrear_id');
            $table->decimal('arrear_int', 20, 5)->nullable();
            $table->decimal('arrear_prin', 20, 5)->nullable();
            $table->decimal('arrear_pen', 20, 5)->nullable();
            $table->decimal('arrear_sav', 20, 5)->nullable();
            $table->date('arrear_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_arrear_pay_details', function (Blueprint $table) {
            $table->integer('tran_id');
            $table->unsignedInteger('arrear_id');
            $table->date('last_pay_arrear_date')->nullable();
            $table->decimal('int_pay', 20, 5)->nullable();
            $table->decimal('prin_pay', 20, 5)->nullable();
            $table->decimal('pen_pay', 20, 5)->nullable();
            $table->decimal('sav_pay', 20, 5)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_custom_schedules', function (Blueprint $table) {
            $table->increments('schedule_custom_id');
            $table->unsignedInteger('loan_schedule_id')->nullable();
            $table->decimal('savings', 20, 5)->nullable();
            $table->integer('int_pay_late')->nullable()->comment('1=Fixed,2=Auto');
            $table->integer('pay_status')->default(0);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_custom_schedule_details', function (Blueprint $table) {
            $table->increments('schedule_custom_detailt_id');
            $table->unsignedInteger('schedule_custom_id')->nullable();
            $table->date('pay_date')->nullable();
            $table->decimal('principal', 20, 5)->nullable();
            $table->decimal('interest', 20, 5)->nullable();
            $table->integer('pay_status')->default(0);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_custom_schedule_detail_tmps', function (Blueprint $table) {
            $table->increments('tmp_schedule_custom_detailt_id');
            $table->unsignedInteger('tmp_schedule_custom_id')->nullable();
            $table->date('pay_date')->nullable();
            $table->decimal('principal', 20, 5)->nullable();
            $table->decimal('interest', 20, 5)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_custom_schedule_tmps', function (Blueprint $table) {
            $table->increments('tmp_schedule_custom_id');
            $table->unsignedInteger('loan_schedule_id')->nullable();
            $table->decimal('savings', 20, 5)->nullable();
            $table->integer('int_pay_late')->nullable()->comment('1=Fixed,2=Auto');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_tran_types', function (Blueprint $table) {
            $table->tinyInteger('loan_tran_type_id')->primary();
            $table->string('loan_tran_type', 50)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('loan_trans', function (Blueprint $table) {
            $table->bigIncrements('loan_tran_id');
            $table->unsignedBigInteger('tran_id')->nullable();
            $table->unsignedInteger('loan_schedule_id')->nullable();
            $table->tinyInteger('loan_tran_type_id')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('amount', 20, 5)->nullable();
            $table->decimal('os_balance', 20, 5)->nullable();

            $table->index('loan_tran_type_id');
            $table->index('tran_id');
            $table->index('loan_schedule_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create all other remaining tables
        Schema::create('modules', function (Blueprint $table) {
            $table->unsignedInteger('module_id')->primary();
            $table->string('module', 50)->nullable();
            $table->string('control_name', 25)->nullable();
            $table->string('url', 50)->nullable();
            $table->smallInteger('type')->default(1)->comment('1=all, 2=branch, 3=head office');
            $table->smallInteger('status')->default(0);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('nationalitys', function (Blueprint $table) {
            $table->increments('nationality_id');
            $table->string('nationality', 255)->nullable();
            $table->string('nationality_kh', 255)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('non_working_days', function (Blueprint $table) {
            $table->string('non_work_day', 50)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('od_limits', function (Blueprint $table) {
            $table->bigInteger('od_limit_id')->primary();
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->decimal('limit_amt', 20, 5)->nullable();
            $table->date('granted_date')->nullable();
            $table->string('limit_remark', 250)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('passbooks', function (Blueprint $table) {
            $table->increments('passbook_id');
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->integer('passbook_no')->nullable();
            $table->tinyInteger('last_printed_page')->nullable();
            $table->tinyInteger('last_printed_line')->nullable();
            $table->string('status', 50)->nullable();

            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('passbook_issues', function (Blueprint $table) {
            $table->increments('pass_issue_id');
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->string('passbook_no', 255)->nullable();
            $table->tinyInteger('last_printed_page')->nullable();
            $table->tinyInteger('last_printed_line')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedInteger('issue_by')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('passbook_maintenances', function (Blueprint $table) {
            $table->increments('pass_id');
            $table->date('tran_date')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('qty')->nullable();
            $table->string('pass_from_no', 255)->nullable();
            $table->string('pass_to_no', 255)->nullable();
            $table->unsignedInteger('main_by')->nullable();
            $table->dateTime('main_date')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('pendding_cash_transfers', function (Blueprint $table) {
            $table->integer('pendding_cash_transfer_id');
            $table->decimal('amount', 20, 5)->nullable();
            $table->char('in_ou', 1)->nullable();
            $table->unsignedTinyInteger('ccy_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('sent_date')->nullable();
            $table->text('remark')->nullable();
            $table->integer('status_id')->nullable()->comment('0="pendding",1="Receipt"');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('public_holidays', function (Blueprint $table) {
            $table->increments('holiday_id');
            $table->date('holiday_date');
            $table->char('repeat', 1)->nullable()->comment('M=MONTHLY, Y=YEARLY');
            $table->string('description', 250)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('purpose_loans', function (Blueprint $table) {
            $table->tinyIncrements('purpose_id');
            $table->string('purpose_type', 200)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('sa_cas', function (Blueprint $table) {
            $table->unsignedBigInteger('acct_id')->nullable();
            $table->decimal('extra_rate', 5, 2)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->date('updated_date')->nullable();

            $table->index('acct_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::create('tran_dates', function (Blueprint $table) {
            $table->char('tran_date', 11)->primary()->comment('06/JUN/2014');
            $table->string('started_by', 50);
            $table->dateTime('started_date');
            $table->string('ended_by', 50)->nullable();
            $table->dateTime('ended_date')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Drop all tables in reverse order
        Schema::dropIfExists('tran_dates');
        Schema::dropIfExists('sa_cas');
        Schema::dropIfExists('purpose_loans');
        Schema::dropIfExists('public_holidays');
        Schema::dropIfExists('pendding_cash_transfers');
        Schema::dropIfExists('passbook_maintenances');
        Schema::dropIfExists('passbook_issues');
        Schema::dropIfExists('passbooks');
        Schema::dropIfExists('od_limits');
        Schema::dropIfExists('non_working_days');
        Schema::dropIfExists('nationalitys');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('loan_trans');
        Schema::dropIfExists('loan_tran_types');
        Schema::dropIfExists('loan_custom_schedule_tmps');
        Schema::dropIfExists('loan_custom_schedule_detail_tmps');
        Schema::dropIfExists('loan_custom_schedule_details');
        Schema::dropIfExists('loan_custom_schedules');
        Schema::dropIfExists('loan_arrear_pay_details');
        Schema::dropIfExists('loan_arrear_details');
        Schema::dropIfExists('loan_arears');
        Schema::dropIfExists('joint_account_holders');
        Schema::dropIfExists('int_rates');
        Schema::dropIfExists('group_tmps');
        Schema::dropIfExists('group_detail_tmps');
        Schema::dropIfExists('group_details');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('fixed_asset_types');
        Schema::dropIfExists('fixed_asset_depres');
        Schema::dropIfExists('fixed_assets');
        Schema::dropIfExists('fd_trans');
        Schema::dropIfExists('fd_roll_overs');
        Schema::dropIfExists('fd_future_deps');
        Schema::dropIfExists('fd_certs');
        Schema::dropIfExists('fd_terms');
        Schema::dropIfExists('fd_options');
        Schema::dropIfExists('ex_rate_historys');
        Schema::dropIfExists('cust_unclears');
        Schema::dropIfExists('cust_photos');
        Schema::dropIfExists('cust_acct_tran_tmps');
        Schema::dropIfExists('cust_acct_trans');
        Schema::dropIfExists('cust_acct_holds');
        Schema::dropIfExists('collateral_type_details');
        Schema::dropIfExists('collateral_release_tmps');
        Schema::dropIfExists('collateral_releases');
        Schema::dropIfExists('collateral_detail_tmps');
        Schema::dropIfExists('collateral_details');
        Schema::dropIfExists('cheque_issueds');
        Schema::dropIfExists('cheque_statuss');
        Schema::dropIfExists('cheque_cleareds');
        Schema::dropIfExists('cust_asset_details');
        Schema::dropIfExists('cust_income_historys');
        Schema::dropIfExists('tran_detail_tmps');
        Schema::dropIfExists('tran_details');
        Schema::dropIfExists('tran_tmps');
        Schema::dropIfExists('trans');
        Schema::dropIfExists('gl_maps');
        Schema::dropIfExists('configs');
        Schema::dropIfExists('collateral_tmps');
        Schema::dropIfExists('collaterals');
        Schema::dropIfExists('loan_schedule_tmps');
        Schema::dropIfExists('loan_schedules');
        Schema::dropIfExists('payment_frequencys');
        Schema::dropIfExists('collateral_types');
        Schema::dropIfExists('cheque_stops');
        Schema::dropIfExists('cheque_maintenances');
        Schema::dropIfExists('cheque_issues');
        Schema::dropIfExists('cheque_clears');
        Schema::dropIfExists('cash_mgts');
        Schema::dropIfExists('branch_trans');
        Schema::dropIfExists('asset_types');
        Schema::dropIfExists('accrued_ints');
        Schema::dropIfExists('account_infos');
        Schema::dropIfExists('account_types');
        Schema::dropIfExists('gls');
        Schema::dropIfExists('gl_l4s');
        Schema::dropIfExists('gl_l3s');
        Schema::dropIfExists('gl_l2s');
        Schema::dropIfExists('gl_l1s');
        Schema::dropIfExists('currencys');
        Schema::dropIfExists('customer_infos');
        Schema::dropIfExists('user_logins');
        Schema::dropIfExists('staffs');
        Schema::dropIfExists('branchs');
        Schema::dropIfExists('villages');
        Schema::dropIfExists('communes');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('access_profile_details');
        Schema::dropIfExists('access_profiles');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }
};
