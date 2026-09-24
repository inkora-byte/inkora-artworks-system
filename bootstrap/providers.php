<?php

use App\Providers\AppServiceProvider;
use App\Providers\TelescopeServiceProvider;

// return [
//     AppServiceProvider::class,
//     TelescopeServiceProvider::class,
// ];

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
];
