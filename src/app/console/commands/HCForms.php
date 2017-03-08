<?php

namespace interactivesolutions\honeycombacl\app\console\commands;

use Cache;
use Carbon\Carbon;
use interactivesolutions\honeycombcore\commands\HCCommand;

class HCForms extends HCCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:forms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through honeycomb related packages and get all form items';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $this->comment ('Scanning form items..');
        $this->generateFormData ();
        $this->comment ('-');
    }

    /**
     * Generating form data
     */
    private function generateFormData ()
    {
        $files = $this->getConfigFiles ();
        $formDataHolder = [];


        if (!empty($files)) {
            foreach ($files as $file) {

                $file = json_decode (file_get_contents ($file), true);

                if (isset($file['formData']))
                    $formDataHolder = array_merge ($formDataHolder, $file['formData']);
            }
        }

        Cache::forget ('hc-forms');
        Cache::put ('hc-forms', $formDataHolder, Carbon::now ()->addMonth ());
    }
}
