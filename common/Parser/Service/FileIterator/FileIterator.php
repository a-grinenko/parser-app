<?php

declare(strict_types=1);

namespace Common\Parser\Service\FileIterator;

use Common\Parser\Interface\FileInterface;
use Common\Parser\ValueObject\File;
use Psr\Container\ContainerInterface;

readonly class FileIterator
{
    public function __construct(
        private ContainerInterface $fileParserLocator,
    ) {
    }

    /** @param class-string<\Common\Parser\Interface\EntityBuilderInterface> $builderClass */
    public function iterate(
        File $file,
        bool $skipFirstRow,
        string $builderClass,
    ): \Traversable {
        // Resolve parser by file type using a service locator (could also be a Factory, etc.).
        // The mapping of a file type (e.g., CSV) to a concrete parser class is defined in services.yaml,
        // which makes it easy to extend the system with new file types in the future.
        /** @var FileInterface $fileParser */
        $fileParser = $this->fileParserLocator->get($file->type->name);

        $fileParser->open(
            $file,
            $skipFirstRow,
            $builderClass,
        );

        yield from $fileParser->parse();

        $fileParser->close();
    }
}
