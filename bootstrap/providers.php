<?php

use App\Providers\AppServiceProvider;
use App\Providers\CatatuServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\DriverPanelProvider;

return [
    AppServiceProvider::class,
    CatatuServiceProvider::class,
    AdminPanelProvider::class,
    DriverPanelProvider::class,
];
