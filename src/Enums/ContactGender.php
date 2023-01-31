<?php

namespace App\Enums;

use App\Traits\Enums\HasEnumTrait;

enum ContactGender: int
{
    use HasEnumTrait;

    case MALE = 0;
    case FEMALE = 1;
    case OTHER = 2;

}
