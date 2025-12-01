<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('renewals:check')->daily();
