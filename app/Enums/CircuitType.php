<?php

namespace App\Enums;

enum CircuitType: string
{
    case UtilityGrid = 'utility_grid';
    case Production = 'production';
    case Consumption = 'consumption';
}
