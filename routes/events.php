<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use App\Listeners\LogRegisteredUser;
use App\Events\TaskCompleted;
use App\Listeners\AwardCommissionOnTaskCompletion;
use App\Listeners\AwardJoiningBonus;

Event::listen(Registered::class, LogRegisteredUser::class);
Event::listen(Login::class, LogSuccessfulLogin::class);
Event::listen(Logout::class, LogSuccessfulLogout::class);
Event::listen(TaskCompleted::class, AwardCommissionOnTaskCompletion::class);
//Event::listen(Registered::class, AwardJoiningBonus::class);
