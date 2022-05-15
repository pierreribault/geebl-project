<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_owner')->default(false);
            $table->boolean('is_redactor')->default(false);
            $table->boolean('is_reviewer')->default(false);
            $table->boolean('is_consumer')->default(false);
            $table->foreignIdFor(Company::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_owner');
            $table->dropColumn('is_redactor');
            $table->dropColumn('is_reviewer');
            $table->dropColumn('is_consumer');
            $table->dropColumn('company_id');
        });
    }
};
