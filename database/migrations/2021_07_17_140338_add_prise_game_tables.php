<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriseGameTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bankAccount');
        });

        Schema::create('bonus_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('balance');
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id', 'FK_bonus_accounts_user_id_foreign_key')
                ->references('id')
                ->on('users');
        });

        Schema::create('bonus_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->double('amount');
            $table->timestamp('created_at')->nullable();

            $table->index('account_id');

            $table->foreign('account_id', 'FK_bonus_transactions_account_id_foreign_key')
                ->references('id')
                ->on('bonus_accounts');
        });


        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account');
            $table->double('balance');
            $table->timestamps();

            $table->index('account');
        });

        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->double('amount');
            $table->timestamp('created_at')->nullable();

            $table->index('account_id');

            $table->foreign('account_id', 'FK_bank_transactions_account_id_foreign_key')
                ->references('id')
                ->on('bank_accounts');
        });

        Schema::create('money_prizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('amount');
            $table->boolean('withdrawn')->default(false);
            $table->boolean('converted')->default(false);
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id', 'FK_money_prizes_user_id_foreign_key')
                ->references('id')
                ->on('users');
        });

        Schema::create('bonus_prizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->double('amount');
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id', 'FK_bonus_prizes_user_id_foreign_key')
                ->references('id')
                ->on('users');
        });

        Schema::create('subject_prizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 256);
            $table->boolean('refused')->default(false);
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id', 'FK_subject_prizes_user_id_foreign_key')
                ->references('id')
                ->on('users');
        });

        Schema::create('prize_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('limit')->nullable();
            $table->string('prize');
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id', 'FK_prize_limits_user_id_foreign_key')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prize_limits');

        Schema::dropIfExists('subject_prizes');
        Schema::dropIfExists('bonus_prizes');
        Schema::dropIfExists('money_prizes');

        Schema::dropIfExists('bank_transactions');
        Schema::dropIfExists('bank_accounts');

        Schema::dropIfExists('bonus_transactions');
        Schema::dropIfExists('bonus_accounts');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bankAccount');
        });
    }
}
