<?php

namespace App\Http\Adapters\Contracts;

use App\Models\IntegrationItems;
use Illuminate\Support\Facades\Http;

abstract class NfBase {

    protected IntegrationItems $integrationItem;

    public function __construct(IntegrationItems $integrationItem) {
        $this->integrationItem = $integrationItem;
    }

    public function startLog() {
        echo "\n";
        echo "Parceiro: {$this->integrationItem->integration->partner->name} \n";
        echo "Adaptador: {$this->integrationItem->adapter} \n";
    }

    public function addLogLine(string $text) {
        echo "{$text}\n";
    }

    public function endLog() {
        echo "\n-------------------- Final da Importação Das NFe's --------------------\n";
    }

    public function checkServer() {
        try {
            Http::get($this->integrationItem->integration->url)->successful();
            $this->addLogLine("Servidor: Conectado em: {$this->integrationItem->integration->url}");
            return true;
        } catch (\Exception $exception) {
            $this->addLogLine("Servidor: Inacessível ({$exception->getMessage()})");
            return false;
        }
    }

}
