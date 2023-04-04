<?php

namespace BahriCanli\netgsm\Http\Clients;

use BahriCanli\netgsm\ShortMessage;
use BahriCanli\netgsm\ShortMessageCollection;
use BahriCanli\netgsm\Http\Responses\netgsmResponseInterface;

/**
 * Interface netgsmClientInterface.
 */
interface netgsmClientInterface
{
    /**
     * Send a short message using the netgsm services.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return netgsmResponseInterface
     */
    public function sendShortMessage(ShortMessage $shortMessage);

    /**
     * Send multiple short messages using the netgsm services.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return netgsmResponseInterface
     */
    public function sendShortMessages(ShortMessageCollection $shortMessageCollection);
}
