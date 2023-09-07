<?php

namespace App\Console\Commands;

use App\Services\Integrations\NfExitIntegration;
use Illuminate\Console\Command;

class NfIntegrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nfintegrations:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Integração das Notas Fiscais';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        NfExitIntegration::run();
        return Command::SUCCESS;
    }
}
