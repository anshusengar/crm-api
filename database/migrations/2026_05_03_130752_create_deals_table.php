<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('deals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lead_id')->constrained()->onDelete('cascade');
        $table->decimal('value', 10, 2);
        $table->enum('stage', ['prospect', 'negotiation', 'won', 'lost'])->default('prospect');
        $table->date('expected_close_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
