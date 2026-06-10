<?php

namespace App\Channels;

class FonnteMessage
{
    public ?string $target = null;

    public string $content;

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public static function create(string $content = ''): static
    {
        return new static($content);
    }

    public function to(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
