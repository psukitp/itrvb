<?php

namespace my\Model;

use my\Model\Name;

class Person
{
    public function __construct(
        private Name $name,
        private \DateTimeImmutable $regiseredOn
    ) {

    }

    public function __toString(): string
    {
        return $this->name . ' (since ' . $this->regiseredOn->format('Y-m-d') . ')';
    }
}