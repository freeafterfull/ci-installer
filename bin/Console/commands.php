<?php

use FreeAfterFull\App\Console\Commands;

$app->add(new Commands\CreateEncryptionKeyCommand());
$app->add(new Commands\CreateMigrationCommand());
$app->add(new Commands\CreateCrudCommand());

$app->add(new Commands\AddTwigCommand());
$app->add(new Commands\AddAuthCommand());
$app->add(new Commands\AddMigrationCommand());
$app->add(new Commands\AddTranslationCommand());
