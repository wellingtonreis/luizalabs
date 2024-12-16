<?php

class InputDto {
    public function __construct(
        private readonly int $type,
        private readonly float $value,
        private readonly DateTime $createdAt,
        private readonly string $description
    ) {}
}