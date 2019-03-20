<?php
/**
 * This file is part of event-engine/php-logger.
 * (c) 2018-2019 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\Logger;

use EventEngine\Commanding\CommandController;
use EventEngine\Messaging\CommandDispatchResult;
use EventEngine\Messaging\GenericEvent;
use EventEngine\Messaging\Message;

interface LogEngine
{
    public function initializedFromCachedConfig(array &$config): void;

    public function initializedAfterLoadingDescriptions(array &$commandMap, array &$eventMap, array &$queryMap): void;

    public function bootstrapped(string $env, bool $debugMode): void;

    public function dispatchStarted(Message $message): void;

    public function eventListenerCalled($listener, Message $event): void;

    public function queryResolverCalled($resolver, Message $query): void;

    public function preProcessorCalled($preProcessor, Message $orgCommand, Message $returnedCommand): void;

    public function preProcessorReturnedDispatchResult($preProcessor, Message $orgCommand, CommandDispatchResult $result);

    public function contextProviderCalled($contextProvider, Message $command, $returnedContext);

    /**
     * @param callable|CommandController|mixed $controller
     * @param Message $command
     * @param CommandDispatchResult|Message[]|array[] $result
     * @return void
     */
    public function commandControllerCalled($controller, Message $command, $result);

    public function eventPublished(Message $event): void;

    public function newAggregateCreated(string $aggregateType, string $aggregateId, GenericEvent ...$events): void;

    public function existingAggregateChanged(string $aggregateType, string $aggregateId, $oldAggregateState, GenericEvent ...$events);

    public function aggregateStateLoaded(string $aggregateType, string $aggregateId, int $aggregateVersion);

    public function aggregateStateLoadedFromCache(string $aggregateType, string $aggregateId, int $expectedVersion = null);

    public function projectionHandledEvent(string $projectionName, GenericEvent $event);

    public function projectionSetUp(string $projectionName);

    public function projectionDeleted(string $projectionName);
}
