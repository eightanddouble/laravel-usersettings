<?php

use EightAndDouble\UserSettings\Tests\TestClass\ClassTestCase;
use EightAndDouble\UserSettings\Tests\TestCommand\CommandTestCase;
use EightAndDouble\UserSettings\Tests\TestDatabase\DatabaseTestCase;

uses(DatabaseTestCase::class)->in(__DIR__.'/TestDatabase');
uses(CommandTestCase::class)->in(__DIR__.'/TestCommand');
uses(ClassTestCase::class)->in(__DIR__.'/TestClass');
