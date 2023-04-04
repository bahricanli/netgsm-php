<?php

namespace BahriCanli\Netgsm\Http\Clients;

use BahriCanli\Netgsm\ShortMessage;
use BahriCanli\Netgsm\ShortMessageCollection;
use BahriCanli\Netgsm\Http\Responses\NetgsmResponseInterface;

/**
 * Interface NetgsmClientInterface.
 */
interface NetgsmClientInterface
{
    /**
     * Send a short message using the Netgsm services.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return NetgsmResponseInterface
     */
    public function sendShortMessage(ShortMessage $shortMessage);

    /**
     * Send multiple short messages using the Netgsm services.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return NetgsmResponseInterface
     */
    public function sendShortMessages(ShortMessageCollection $shortMessageCollection);
}
