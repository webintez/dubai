<?php

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
        // Add phone and role to users table if not already present
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password'); // 'user' or 'admin'
            }
        });

        // Create meetings table
        if (!Schema::hasTable('meetings')) {
            Schema::create('meetings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('link'); // Meeting URL (Zoom/Meet/Teams)
                $table->string('duration'); // e.g. "45 mins", "1 hour"
                $table->string('password'); // Meeting password (hidden until approved)
                $table->string('price'); // e.g. "150 AED", "50 USD"
                $table->string('thumbnail')->nullable(); // Uploaded image path
                $table->string('status')->default('upcoming'); // 'ongoing', 'upcoming', 'completed'
                $table->dateTime('start_time')->nullable(); // Scheduled start
                $table->timestamps();
            });
        }

        // Create bookings table
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('screenshot_path')->nullable();
                $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
                $table->string('assigned_code')->nullable(); // Assigned by admin on approval
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('meetings');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
