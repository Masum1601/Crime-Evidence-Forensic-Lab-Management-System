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
    protected $description = 'Create or reset all demo users (Admin, Officer, Analyst) with valid bcrypt passwords';

    private array $users = [
        ['role' => 'Admin',   'name' => 'System Admin',  'email' => 'admin@cefl.test',   'password' => 'admin123',   'phone' => '01700000000'],
        ['role' => 'Officer', 'name' => 'John Officer',  'email' => 'officer@cefl.test', 'password' => 'officer123', 'phone' => '01711111111'],
        ['role' => 'Analyst', 'name' => 'Sara Analyst',  'email' => 'analyst@cefl.test', 'password' => 'analyst123', 'phone' => '01722222222'],
    ];

    public function handle(): void
    {
        $rows = [];

        foreach ($this->users as $u) {
            // Ensure role exists
            $role = Role::firstOrCreate(['role_name' => $u['role']]);

            // Generate & verify bcrypt hash
            $hash = Hash::make($u['password']);
            if (!str_starts_with($hash, '$2y$') && !str_starts_with($hash, '$2b$')) {
                $this->error("Hash driver is not bcrypt! Aborting for {$u['email']}");
                continue;
            }

            $exists = DB::table('users')->where('email', $u['email'])->exists();

            if ($exists) {
                DB::table('users')->where('email', $u['email'])
                    ->update(['password' => $hash, 'status' => 'ACTIVE', 'role_id' => $role->role_id]);
                $action = 'Updated';
            } else {
                DB::table('users')->insert([
                    'role_id'   => $role->role_id,
                    'full_name' => $u['name'],
                    'email'     => $u['email'],
                    'password'  => $hash,
                    'phone'     => $u['phone'],
                    'status'    => 'ACTIVE',
                ]);
                $action = 'Created';
            }

            // Verify stored hash
            $stored = DB::table('users')->where('email', $u['email'])->value('password');
            $ok = Hash::check($u['password'], $stored) ? '✅' : '❌';
            $rows[] = [$ok, $action, $u['role'], $u['email'], $u['password']];
        }

        $this->newLine();
        $this->table(['', 'Action', 'Role', 'Email', 'Password'], $rows);
        $this->newLine();
        $this->info('All demo users are ready. Use the credentials above to log in.');
    }
}
