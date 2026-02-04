<?php

declare(strict_types=1);

namespace Common\Parser\Service\Parser;

use Common\Parser\Interface\EntityBuilderInterface;
use Common\Parser\Interface\FileInterface;
use Common\Parser\ValueObject\File;

class CsvParser extends AbstractFileParser implements FileInterface
{
    private const SEPARATOR = ',';

    private $fileHandler;

    private bool $isFirstRow = true;
    private bool $skipFirstRow;

    /** @param class-string<EntityBuilderInterface> $builderClass */
    public function open(
        File $file,
        bool $skipFirstRow,
        string $builderClass,
    ): void {
        $this->setBuilderClass($builderClass);

        $this->fileHandler = fopen($file->filePath, 'r');
        $this->skipFirstRow = $skipFirstRow;
    }

    public function parse(): \Traversable
    {
        while ($row = fgetcsv($this->fileHandler, separator: self::SEPARATOR, escape: '\\')) {
            if ($this->isFirstRow) {
                $this->isFirstRow = false;
                if ($this->skipFirstRow) {
                    continue;
                }
            }

            yield $this->inflate($row);
        }
    }

    public function close(): void
    {
        fclose($this->fileHandler);
    }
}
