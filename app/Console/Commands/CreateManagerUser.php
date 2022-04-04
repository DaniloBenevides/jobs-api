<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class CreateManagerUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:manager-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a manager user';

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

            $user->roles()->attach(2);
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
