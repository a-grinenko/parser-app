<?php

declare(strict_types=1);

namespace Common\Parser\Service\Parser;

use Common\Parser\Interface\EntityBuilderInterface;

abstract class AbstractFileParser
{
    /** @var class-string<EntityBuilderInterface> */
    private string $builderClass;

    /** @param class-string<EntityBuilderInterface> $builderClass */
    public function setBuilderClass(string $builderClass): void
    {
        if (!is_a($builderClass, EntityBuilderInterface::class, true)) {
            throw new \LogicException(
                sprintf('%s must implement %s', $builderClass, EntityBuilderInterface::class)
            );
        }

        $this->builderClass = $builderClass;
    }

    public function inflate(array $data): EntityBuilderInterface
    {
        return ($this->builderClass)::fromScalars($data);
    }
}
