<?php


namespace App\Listener;


use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;

/**
 *
 * Class MultiEnvConfigListener
 * @package App\Listener
 */
class MultiEnvConfigListener implements ListenerInterface {

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array {
        return [
            BootApplication::class
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event) {

    }
}