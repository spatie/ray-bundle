<?php

namespace Spatie\RayBundle\Tests\TestClasses;

class User
{
    private $id;

    private $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function make(array $attributes): self
    {
        return new self($attributes['email']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }
}

