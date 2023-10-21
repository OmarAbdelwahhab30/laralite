<?php

namespace Laralite\Framework\Http;

class Response
{
    public function __construct(
        public string $content = "",
        public int $status = 200,
        public array $headers = [],
    )
    {

    }

    public function send(): void
    {
        echo $this->content;
    }
}