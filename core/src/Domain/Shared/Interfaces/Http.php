<?php

namespace TechChallenge\Domain\Shared\Interfaces;

interface Http
{
    public function post(string $url, array $body): void;
}
