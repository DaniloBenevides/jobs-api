<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateRegularUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:regular-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a regular user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $user = User::factory()->create();

            $user->roles()->attach(1);
            $token = $user->createToken($user->name);
        }catch(Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return 1;
        }

        DB::commit();

        echo "Your token is: ". $token->plainTextToken;
        return 0;
    }
}
