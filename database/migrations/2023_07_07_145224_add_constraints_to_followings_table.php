<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('followings', function (Blueprint $table) {
            DB::statement('ALTER TABLE followings ADD CONSTRAINT con_cannot_follow_oneself CHECK (follower <> followed_user)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('followings', function (Blueprint $table) {
            DB::statement('ALTER TABLE followings DROP CONSTRAINT con_cannot_follow_oneself');
        });
    }
};