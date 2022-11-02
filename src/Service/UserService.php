<?php

namespace App\Service;

use DateTime;

class UserService
{
    public function calculateAge($birthDate)
    {
        $now = new DateTime();
        $difference = $now->diff($birthDate);
        $age = $difference->y;

        return $age;
    }
}