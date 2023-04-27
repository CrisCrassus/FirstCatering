<?php

namespace App\Enums;

enum TransactionTypes:string
{
    case PURCHASE = 'purchase';
    case TOPUP = 'topup';
}
