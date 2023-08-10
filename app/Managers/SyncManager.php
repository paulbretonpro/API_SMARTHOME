<?php

namespace App\Managers;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class SyncManager
{
    public function callCommand($sinceLast = false, $captor = false, $sensor = false, $weather = false)
    {
        $options = [];

        if ($sinceLast) {
            $options['--since-last'] = true;
        }
        if ($captor) {
            $options['--captor'] = true;
        }
        if ($sensor) {
            $options['--sensor'] = true;
        }
        if ($weather) {
            $options['--weather'] = true;
        }

        Artisan::call('data:sync', $options);

        $commandOutput = Artisan::output();

        $result = str_split($commandOutput);

        return [
            'captor' => $result[0],
            'sensor' => $result[1],
            'weather' => $result[2]
        ];
    }
}
