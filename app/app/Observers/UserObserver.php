<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $k = rand(0, 100);
        $user->tg_token = $year + $month + $day + $k;
    }
}
