<?php

use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBookModel;
use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LaravelUserModel::class);
            $table->foreignIdFor(LaravelStoredBookModel::class);
            $table->dateTime('reserved_at')->useCurrent();
            $table->dateTime('returned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
