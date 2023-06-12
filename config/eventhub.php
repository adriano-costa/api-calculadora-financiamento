<?php

return [
    'sas_key_name' => env('EVENT_HUB_SAS_KEY_NAME', 'hack'),
    'sas_key' => env('EVENT_HUB_SAS_KEY', 'xxxxxxxx'),
    'host' => env('EVENT_HUB_HOST', 'hack.servicebus.windows.net'),
    'entity_path' => env('EVENT_HUB_ENTITY_PATH', 'simulacoes'),
    'validar_certificado_ssl' => env('EVENT_HUB_VALIDAR_CERTIFICADO_SSL', true),
];
