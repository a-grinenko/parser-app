<?php

namespace Common\Parser\Interface;

interface EntityBuilderInterface extends \JsonSerializable
{
    public static function fromScalars(array $values): static;
}
