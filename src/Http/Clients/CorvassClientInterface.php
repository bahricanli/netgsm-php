<?php

namespace BahriCanli\Corvass\Http\Clients;

use BahriCanli\Corvass\ShortMessage;
use BahriCanli\Corvass\ShortMessageCollection;
use BahriCanli\Corvass\Http\Responses\CorvassResponseInterface;

/**
 * Interface CorvassClientInterface.
 */
interface CorvassClientInterface
{
    /**
     * Send a short message using the Corvass services.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return CorvassResponseInterface
     */
    public function sendShortMessage(ShortMessage $shortMessage);

    /**
     * Send multiple short messages using the Corvass services.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return CorvassResponseInterface
     */
    public function sendShortMessages(ShortMessageCollection $shortMessageCollection);
}
