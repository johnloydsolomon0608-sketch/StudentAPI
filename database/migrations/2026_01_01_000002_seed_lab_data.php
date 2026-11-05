<?php
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        (new DatabaseSeeder)->run();
    }

    public function down(): void
    {
        // The preceding migration owns these tables and their sample rows.
    }
};
