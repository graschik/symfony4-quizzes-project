<?php

declare(strict_types=1);

namespace App\Service\Util;


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TokenGenerator
{

    /**
     * @param string $stringForGenerateToken
     * @return string
     */
    public function generate(string $stringForGenerateToken): string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $stringForGenerateToken)->toString();
    }
}