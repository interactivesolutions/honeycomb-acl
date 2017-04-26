<?php

namespace interactivesolutions\honeycombacl\app\console\commands;

use interactivesolutions\honeycombcore\commands\HCCommand;

class HCAdminURL extends HCCommand
{
    const KEY = 'HC_ADMIN_URL';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:admin-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate secure admin url';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $this->generateURL ();
    }

    /**
     * Generate url
     */
    private function generateURL ()
    {
        $url = 'admin' . random_str(8);

        addEnvVariable(self::KEY, $url);

        $this->info("Admin URL $url set successfully.");
    }
}