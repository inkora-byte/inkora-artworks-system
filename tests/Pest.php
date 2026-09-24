<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Feature tests get RefreshDatabase — every test runs against a freshly
| migrated SQLite in-memory database (per phpunit.xml), never against
| real development data. Unit tests don't touch the database at all, so
| they're left without it for speed.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(TestCase::class)->in('Unit');
