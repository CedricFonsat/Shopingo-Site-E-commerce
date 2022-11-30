<?php

namespace App\Utils;

class Functions
{
    public static function slugify(string $str) {
        return mb_strtolower($str);
    }

}