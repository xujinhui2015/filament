<?php

use App\Console\Commands\Mall\AutoFinishOrderCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(AutoFinishOrderCommand::class)->everyFifteenMinutes()->withoutOverlapping(3);
