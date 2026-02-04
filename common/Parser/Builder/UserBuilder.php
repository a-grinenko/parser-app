<?php

namespace Common\Parser\Builder;

use Common\Parser\Interface\EntityBuilderInterface;

final class UserBuilder implements EntityBuilderInterface
{
    private const array TITLES = ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof', 'Mister'];

    public function __construct(
        public ?string $title,
        public ?string $firstName,
        public ?string $lastname,
        public ?string $initial
    ) {
    }

    public static function fromScalars(array $values): static
    {
        $raw = trim($values[0] ?? '');

        // Multiple people separated by " and " — take only the first
        // Split on "and" unless it connects titles (e.g. "Mr and Mrs")
        $titlesRegex = implode('|', self::TITLES);
        $regex = sprintf('/(?<!%s)\s+and\s+/i', $titlesRegex);
        $firstPerson = preg_split($regex, $raw, 2)[0];

        [$title, $firstName, $lastname] = self::parse($firstPerson);

        $initial = $firstName ? strtoupper($firstName[0]) : null;

        return new static($title, $firstName, $lastname, $initial);
    }

    /**
     * @return array{0: ?string, 1: ?string, 2: ?string} [title, firstName, lastname]
     */
    private static function parse(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name));

        if ($parts === false) {
            return [null, null, null];
        }

        // Consume a compound title (e.g. "Mr and Mrs", "Dr & Mrs")
        $title = '';
        while (!empty($parts) && self::isTitle($parts[0])) {
            $part = array_shift($parts);
            $title .= ($title !== '' ? ' ' : '') . $part;

            if (!empty($parts) && in_array($parts[0], ['and', '&'], true)) {
                $title .= ' ' . array_shift($parts);
            }
        }

        $title = $title === '' ? null : $title;

        if (empty($parts)) {
            return [$title, null, null];
        }

        if (count($parts) === 1) {
            // Only one name part -> treat as a last name
            return [$title, null, $parts[0]];
        }

        $lastName = array_pop($parts);
        $firstName = self::stripTrailingDot($parts[0]);

        return [$title, $firstName, $lastName];
    }

    private static function isTitle(string $word): bool
    {
        return in_array($word, self::TITLES, true);
    }

    private static function stripTrailingDot(string $value): string
    {
        return rtrim($value, '.');
    }

    public function jsonSerialize(): mixed
    {
        return [
            'title' => $this->title,
            'firstName' => $this->firstName,
            'lastname' => $this->lastname,
            'initial' => $this->initial,
        ];
    }
}
