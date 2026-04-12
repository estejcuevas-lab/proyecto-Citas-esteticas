<?php

namespace Distributed\Contracts;

interface RemoteSerializable
{
    public function toArray(): array;

    public static function fromArray(array $payload): static;
}
