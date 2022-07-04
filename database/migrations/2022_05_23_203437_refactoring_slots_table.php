<?php

use App\Enums\TicketStatus;
use App\Models\Event;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('slots');

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('date', 'start_at');
            $table->timestamp('end_at');
            $table->dropColumn('price');
            $table->dropColumn('seats');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('status')->default(TicketStatus::NonUsed->value);
            $table->decimal('price');
            $table->foreignIdFor(Event::class);
            $table->foreignIdFor(TicketCategory::class);
            $table->foreignIdFor(User::class)->nullable();
        });

        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->foreignIdFor(Event::class);
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price');
            $table->integer('available_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
