<!-- <?php

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
    Schema::table('users', function (Blueprint $table) {
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('role');
    $table->timestamp('approved_at')->nullable()->after('status');
    $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users');
    $table->string('phone_number')->nullable()->after('email');
});

    Schema::create('login_otps', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('otp', 6);
    $table->timestamp('expires_at');
    $table->boolean('is_used')->default(false);
    $table->string('ip_address', 45)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}; 
