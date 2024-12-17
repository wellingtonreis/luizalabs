<?php 

namespace Src\UseCases;

class Response {
    
    public function __construct(
        public bool $outcome,
        public ?string $message
    ) {}

    public static function success(string $message): Response
    {
        return new self(true, $message);
    }

    public static function error(string $message): Response
    {
        return new self(false, $message);
    }
}