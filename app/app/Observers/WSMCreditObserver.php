<?php

namespace App\Observers;

use App\Models\WSMCredit;
use App\Models\WSMCreditAward;
use App\Models\WSMCreditCalculation;
use App\Models\WSMCreditContract;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class WSMCreditObserver implements ShouldHandleEventsAfterCommit
{

}
