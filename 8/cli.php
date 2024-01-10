<?php

use Psr\Log\LoggerInterface;
use my\Commands\Arguments;
use my\Commands\CreateUserCommand;
use my\Exceptions\CommandException;

$container = require __DIR__ . '/bootstrap.php';
$command = $container->get(CreateUserCommand::class);

$logger = $container->get(LoggerInterface::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (CommandException $error) {
    $logger->error($error->getMessage(), ['exception' => $error]);
}