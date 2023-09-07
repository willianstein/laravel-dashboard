<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Integrations\OrdersExitIntegration;
use App\Services\Integrations\OrdersEntryIntegration;

class OrderIntegrations extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderintegrations:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Integração dos Pedidos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        OrdersExitIntegration::run();
        OrdersEntryIntegration::run();
        return Command::SUCCESS;
    }
}
