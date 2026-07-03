<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class ResetAdminPassword extends Command
{
    protected $signature   = 'admin:reset-password';
    protected $description = 'Create or reset the admin user with a valid bcrypt password';

    public function handle(): void
    {
        $email    = 'admin@cefl.test';
        $password = 'admin123';

        // 1. Ensure the Admin role exists
        $role = Role::firstOrCreate(['role_name' => 'Admin']);
        $this->line("Role ID: {$role->role_id}");

        // 2. Generate hash and verify it is bcrypt before saving
        $hash = Hash::make($password);
        if (!str_starts_with($hash, '$2y$') && !str_starts_with($hash, '$2b$')) {
            $this->error('Hash driver is not bcrypt! Check config/hashing.php');
            return;
        }
        $this->line("Hash preview: " . substr($hash, 0, 7) . '...');

        // 3. Update or create the user using a raw DB update to bypass any
        //    Eloquent casting / Oracle driver quirks
        $exists = DB::table('users')->where('email', $email)->exists();

        if ($exists) {
            DB::table('users')
                ->where('email', $email)
                ->update(['password' => $hash, 'status' => 'ACTIVE']);
            $this->info("✅ Password updated for existing user: {$email}");
        } else {
            DB::table('users')->insert([
                'role_id'   => $role->role_id,
                'full_name' => 'Admin',
                'email'     => $email,
                'password'  => $hash,
                'phone'     => '01700000000',
                'status'    => 'ACTIVE',
            ]);
            $this->info("✅ Admin user created: {$email}");
        }

        // 4. Read back the stored hash and verify it
        $stored = DB::table('users')->where('email', $email)->value('password');
        $this->line("Stored hash preview: " . substr($stored, 0, 7) . '...');

        if (Hash::check($password, $stored)) {
            $this->info("✅ Hash verification passed — you can now log in.");
            $this->newLine();
            $this->table(['Field', 'Value'], [
                ['Email',    $email],
                ['Password', $password],
            ]);
        } else {
            $this->error('❌ Hash verification FAILED — something went wrong with storage.');
        }
    }
}
