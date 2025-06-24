<?php

namespace App\Enums;

enum CircuitType: string
{
    case GridUtility = 'grid_utility';
    case Production = 'production';
    case Consumption = 'consumption';
}
