<?php

namespace App\Models\DataTransferObjects;

use DateTime;

class OrderExitDto extends OrderDto {

    public function __construct(
        int $office_id,
        int $partner_id,
        string $status,
        int $id = null,
        int $recipient_id = null,
        int $transport_id = null,
        string $invoice = null,
        string $content_declaration = null,
        DateTime $forecast = null, string
        $third_system = null,
        string $third_system_id = null,
        string $observations = null
    ) {
        parent::__construct(
            'saida',
            $office_id,
            $partner_id,
            $status,
            $id,
            $recipient_id,
            $transport_id,
            $invoice,
            $content_declaration,
            $forecast,
            $third_system,
            $third_system_id,
            $observations
        );
    }

}
