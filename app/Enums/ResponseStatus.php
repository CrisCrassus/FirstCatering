<?php

namespace App\Enums;

enum ResponseStatus:string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case ERROR = 'error';
}
