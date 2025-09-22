<?php

namespace App\Controllers\Services;

class GenerateUuid
{

    public static function generateuuid(): string
    {
        return random_int(10000000, 99999999);
    }
}
