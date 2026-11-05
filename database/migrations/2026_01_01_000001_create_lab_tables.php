<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email')->unique();
            $t->string('password');
            $t->rememberToken();
            $t->timestamps();
        });
        Schema::create('personal_access_tokens', function (Blueprint $t) {
            $t->id();
            $t->morphs('tokenable');
            $t->text('name');
            $t->string('token', 64)->unique();
            $t->text('abilities')->nullable();
            $t->timestamp('last_used_at')->nullable();
            $t->timestamp('expires_at')->nullable()->index();
            $t->timestamps();
        });
        Schema::create('departments', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100)->unique();
            $t->timestamps();
        });
        Schema::create('employees', function (Blueprint $t) {
            $t->id();
            $t->string('first_name', 100);
            $t->string('last_name', 100);
            $t->string('email')->unique();
            $t->string('position', 100);
            $t->foreignId('department_id')->constrained()->restrictOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['employees', 'departments', 'personal_access_tokens', 'users'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
