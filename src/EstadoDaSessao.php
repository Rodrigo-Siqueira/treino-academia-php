<?php

declare(strict_types=1);

namespace App;

enum EstadoDaSessao
{
    case EM_ANDAMENTO;
    case CONCLUIDA;
    case CANCELADA;
}
