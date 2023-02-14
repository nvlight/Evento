<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Set role for user';

    public function handle(): bool
    {
        $this->table(
            ['id', 'name', 'email', 'email_verified_at'],
            User::all(['id', 'name', 'email', 'email_verified_at'])->toArray()
        );

        // создание прогресс-бара
//        $users = User::all();
//        $bar = $this->output->createProgressBar(count($users));
//
//        $bar->start();
//
//        foreach ($users as $user) {
//            sleep(1);
//            $bar->advance();
//        }
//
//        $bar->finish();
//        return true;

        $this->trap([SIGTERM, SIGQUIT], fn() => $this->shouldKeepRunning = false);

        $name = $this->ask('What is your name?');
        $pass = $this->secret('And your password, please ?');
        $v18_plus = $this->confirm('Are you 18 year old?');
        $this->info("Your name is: {$name}");
        $this->info("Your pass is: {$pass}");
        $this->info("Your 18+ is: {$v18_plus}");

        $email = $this->argument('email');
        $role = $this->argument('role');

        /** @var User $user */
        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }

        try {
            $user->changeRole($role);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }

        $this->info('Role is successfully changed');
        return true;
    }
}
