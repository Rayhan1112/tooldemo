<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE scheduled_emails MODIFY COLUMN status ENUM('pending', 'sending', 'sent', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE scheduled_emails MODIFY COLUMN status ENUM('pending', 'sent', 'failed') DEFAULT 'pending'");
    }
};