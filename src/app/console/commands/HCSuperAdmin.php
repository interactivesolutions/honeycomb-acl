<?php

namespace interactivesolutions\honeycombacl\app\console\commands;

use Carbon\Carbon;
use DB;
use interactivesolutions\honeycombacl\app\models\HCUsers;
use interactivesolutions\honeycombcore\commands\HCCommand;
use Validator;

class HCSuperAdmin extends HCCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates super admin account or updates its password';

    /**
     * Admin password holder
     *
     * @var
     */
    private $password;

    /**
     * Admin email holder
     *
     * @var
     */
    private $email;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createSuperAdmin();
    }

    /**
     * Get email address
     *
     * @return mixed
     */
    private function getEmail()
    {
        $email = $this->ask("Enter email address");

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|min:3|email',
        ]);

        if( $validator->fails() ) {
            $this->error('Email is required, minimum 3 symbols length and must be email format');

            return $this->getEmail();
        }

        $this->email = $email;
    }

    /**
     * Create super admin account
     */
    private function createSuperAdmin()
    {
        $this->getEmail();

        $this->info('');
        $this->comment('Creating default super-admin user...');
        $this->info('');

        $this->checkIfAdminExists();

        $this->getPassword();

        $this->createAdmin();

        $this->comment('Super admin account successfully created!');
        $this->comment('Your email: ');
        $this->error($this->email);

        $this->info('');
    }

    /**
     * Change password
     *
     * @param $admin
     */
    private function changePassword($admin)
    {
        $this->getPassword();

        $admin->password = $this->password;
        $admin->save();

        $this->info('Password has been updated!');
        exit;
    }

    /**
     * Validates password
     *
     * @return mixed
     */
    private function getPassword()
    {
        $password = $this->secret("Enter your password");
        $passwordAgain = $this->secret("Enter your password again");

        $validator = Validator::make([
            'password'              => $password,
            'password_confirmation' => $passwordAgain,
        ], [
            'password' => 'required|min:5|confirmed',
        ]);

        if( $validator->fails() ) {
            $this->info('');
            $this->error("The password must be at least 5 characters and must match!");
            $this->info('');

            return $this->getPassword();
        }

        $this->password = bcrypt($password);
    }

    /**
     * Create super admin role and assign role
     */
    private function createAdmin()
    {
        DB::beginTransaction();

        try {
            // create super-admin user
            $user = HCUsers::create(['email' => $this->email, 'password' => $this->password, 'activated_at' => Carbon::now()->toDateTimeString()]);
            $user->assignRole('super-admin');

        } catch ( \Exception $e ) {
            DB::rollback();

            $this->error('Super admin role doesn\'t exists!');
            $this->info('error:');
            $this->error($e->getMessage());

            exit;
        }

        DB::commit();
    }

    /**
     * Check if super admin exists
     */
    private function checkIfAdminExists()
    {
        $adminExists = HCUsers::where('email', $this->email)->first();

        if( ! is_null($adminExists) ) {

            $this->checkIfHaveSuperAdminRole($adminExists);

            $this->info('Admin account already exists!');

            if( $this->confirm('Do you want to change its password? [y|N]') ) {
                $this->changePassword($adminExists);
            }

            exit;
        }
    }

    /**
     * Function which checks if admin user has super-admin role
     *
     * @param $adminExists
     */
    private function checkIfHaveSuperAdminRole($adminExists)
    {
        $hasRole = $adminExists->hasRole('super-admin');

        if( ! $hasRole ) {
            $this->comment("{$this->email} account doesn't have super-admin role!");

            if( $this->confirm('Do you want to add super-admin role? [y|N]') ) {

                DB::beginTransaction();

                try {
                    $adminExists->assignRole('super-admin');
                } catch ( \Exception $e ) {
                    DB::rollback();

                    $this->error($e->getMessage());
                    exit;
                }

                $this->info('Super admin role has been added');

                DB::commit();
            }

            exit;
        }
    }
}
