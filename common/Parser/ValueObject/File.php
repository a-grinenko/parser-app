<?php

declare(strict_types=1);

namespace Common\Parser\ValueObject;

use Common\Parser\Enum\FileType;

final readonly class File implements \Stringable
{
    // TODO: support different file types (e.g. XLS, XLSX, TXT) by adding
    // the corresponding MIME constants, FileType enum cases, match arms below,
    // and registering the parsers in the service locator. Example:
    //    private const MIME_XLS = 'application/vnd.ms-excel';
    //    private const MIME_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    //    private const MIME_TEXT = 'text/plain';

    private const MIME_CSV = 'text/csv';

    public FileType $type;

    public function __construct(
        public string $filePath,
    ) {
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException('File is not exist');
        }

        if (!is_readable($this->filePath)) {
            throw new \RuntimeException('File is not readable');
        }

        $this->type = match (\mime_content_type($this->filePath)) {
            self::MIME_CSV => FileType::CSV,
            default => throw new \Exception('Incompatible MIME file type'),
        };
    }

    public function __toString(): string
    {
        return $this->filePath;
    }
}
