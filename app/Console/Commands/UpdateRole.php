<?php

namespace App\Console\Commands;

use App\Enums\Role\Role;
use Illuminate\Console\Command;

class UpdateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $roles_for_db = Role::forDB();

        $progress_bar = $this->output->createProgressBar(count($roles_for_db));

        foreach ($roles_for_db as $role) {
            $progress_bar->advance();
            \Spatie\Permission\Models\Role::query()->createOrFirst($role);
        }

        $progress_bar->finish();

        $this->output->newLine();

        $this->components->info('Roles updated');
    }
}
