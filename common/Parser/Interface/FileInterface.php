<?php

declare(strict_types=1);

namespace Common\Parser\Interface;

use Common\Parser\ValueObject\File;

interface FileInterface
{
    /** @param class-string<EntityBuilderInterface> $builderClass */
    public function open(File $file, bool $skipFirstRow, string $builderClass): void;

    public function parse(): \Traversable;

    public function close(): void;
}
