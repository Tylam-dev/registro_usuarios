<?php

namespace App\Domain;

enum EstadoCivilEnum: string
{
    case SOLTERO    = 'soltero';
    case CASADO     = 'casado';
    case VIUDO      = 'viudo';
    case DIVORCIADO = 'divorciado';
}