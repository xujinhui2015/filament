<?php

namespace App\Support\Traits;

use DateTimeInterface;

trait FormatModelDateTrait
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
