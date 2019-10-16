<?php

namespace App\Service;

class Calculator
{
    public function calculateAge(int $birthYear): int
    {
        return date('Y') - $birthYear;
    }
}
