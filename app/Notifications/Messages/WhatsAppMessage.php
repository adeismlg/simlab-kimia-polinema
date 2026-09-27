<?php

namespace App\Notifications\Messages;

class WhatsAppMessage
{
    public string $content = '';

    public static function create(string $content = ''): self
    {
        return (new self())->content($content);
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
