<?php

namespace interactivesolutions\honeycombacl\database\seeds;

use DB;
use Exception;
use HCLog;
use Illuminate\Database\Seeder;
use interactivesolutions\honeycombacl\app\models\acl\Roles;

class UserRolesSeeder extends Seeder
{
    const ROLE_SA = 'super-admin';
    const ROLE_PA = 'project-admin';
    const ROLE_E  = 'editor';
    const ROLE_A  = 'author';
    const ROLE_C  = 'contributor';
    const ROLE_M  = 'moderator';
    const ROLE_ME = 'member';
    const ROLE_S  = 'subscriber';
    const ROLE_U  = 'user';

    /**
     * Run the database seeds.
     * @return void
     * @throws Exception
     */
    public function run ()
    {
        // http://stackoverflow.com/q/1598411
        $list = [
            ["name" => "Super Admin", "slug" => "super-admin"], // Manage everything
            ["name" => "Project Admin", "slug" => "project-admin"], // Manage most aspects of the site
            ["name" => "Editor", "slug" => "editor"], // Scheduling and managing content
            ["name" => "Author", "slug" => "author"], // Write important content
            ["name" => "Contributor", "slug" => "contributor"], // Authors with limited rights
            ["name" => "Moderator", "slug" => "moderator"], // Moderate user content
            ["name" => "Member", "slug" => "member"], // Special user access
            ["name" => "Subscriber", "slug" => "subscriber"], // Paying Average Joe
            ["name" => "User", "slug" => "user"], // Average Joe
        ];

        DB::beginTransaction ();

        try {
            foreach ($list as $roleData) {
                $role = Roles::where ('slug', $roleData['slug'])->first ();

                if (!$role)
                    Roles::create ($roleData);
            }
        } catch (\Exception $e) {
            DB::rollback ();

            throw new Exception($e->getMessage ());
        }

        DB::commit ();
    }
}