<?php

namespace interactivesolutions\honeycombacl\console\commands;

use Cache;
use Carbon\Carbon;
use interactivesolutions\honeycombcore\commands\HCCommand;

class GenerateAdminMenu extends HCCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:admin-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through honeycomb related packages and get all menu items';

    /**
     * Menu list holder
     *
     * @var array
     */
    private $adminMenuHolder = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $this->comment ('Scanning menu items..');
        $this->generateMenu ();
        $this->comment ('-');
    }

    /**
     * Get admin menu
     */
    private function generateMenu ()
    {
        $files = $this->getConfigFiles ();

        if (!empty($files)) {
            foreach ($files as $file) {

                $file = json_decode (file_get_contents ($file), true);

                if (isset($file['adminMenu']))
                    $this->adminMenuHolder = array_merge ($this->adminMenuHolder, $file['adminMenu']);
            }
        }

        Cache::forget ('hc-admin-menu');
        Cache::put ('hc-admin-menu', $this->adminMenuHolder, Carbon::now ()->addWeek ());
    }
}
