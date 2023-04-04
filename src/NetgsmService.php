<?php

namespace BahriCanli\Netgsm;

use BahriCanli\Netgsm\Http\Clients\NetgsmClientInterface;
use BahriCanli\Netgsm\Http\Responses\NetgsmResponseInterface;

/**
 * Class NetgsmService.
 */
final class NetgsmService
{
    /**
     * The Netgsm client implementation.
     *
     * @var NetgsmClientInterface
     */
    private $client;

    /**
     * The short message factory implementation.
     *
     * @var ShortMessageFactoryInterface
     */
    private $factory;

    /**
     * The short message collection factory implementation.
     *
     * @var ShortMessageCollectionFactoryInterface
     */
    private $collectionFactory;

    /**
     * The before callback which will be called before sending single messages.
     *
     * @var callable|null
     */
    private $beforeSingleShortMessageCallback;

    /**
     * The after callback which will be called before sending single messages.
     *
     * @var callable|null
     */
    private $afterSingleShortMessageCallback;

    /**
     * The before callback which will be called before sending multiple messages.
     *
     * @var callable|null
     */
    private $beforeMultipleShortMessageCallback;

    /**
     * The after callback which will be called after sending multiple messages.
     *
     * @var callable|null
     */
    private $afterMultipleShortMessageCallback;

    /**
     * NetgsmService constructor.
     *
     * @param  NetgsmClientInterface                  $NetgsmClient
     * @param  ShortMessageFactoryInterface           $shortMessageFactory
     * @param  ShortMessageCollectionFactoryInterface $shortMessageCollectionFactory
     * @param  callable|null                          $beforeSingleShortMessageCallback
     * @param  callable|null                          $afterSingleShortMessageCallback
     * @param  callable|null                          $beforeMultipleShortMessageCallback
     * @param  callable|null                          $afterMultipleShortMessageCallback
     */
    public function __construct(
        NetgsmClientInterface $NetgsmClient,
        ShortMessageFactoryInterface $shortMessageFactory,
        ShortMessageCollectionFactoryInterface $shortMessageCollectionFactory,
        $beforeSingleShortMessageCallback = null,
        $afterSingleShortMessageCallback = null,
        $beforeMultipleShortMessageCallback = null,
        $afterMultipleShortMessageCallback = null
    ) {
        $this->client = $NetgsmClient;
        $this->factory = $shortMessageFactory;
        $this->collectionFactory = $shortMessageCollectionFactory;
        $this->beforeSingleShortMessageCallback = $beforeSingleShortMessageCallback;
        $this->afterSingleShortMessageCallback = $afterSingleShortMessageCallback;
        $this->beforeMultipleShortMessageCallback = $beforeMultipleShortMessageCallback;
        $this->afterMultipleShortMessageCallback = $afterMultipleShortMessageCallback;
    }

    /**
     * Send the given body to the given receivers.
     *
     * @param  array|string|ShortMessage $receivers The receiver(s) of the message or the message object.
     * @param  string|null               $body      The body of the message or null when using short message object.
     *
     * @return NetgsmResponseInterface The parsed Netgsm response object.
     */
    public function sendShortMessage($receivers, $body = null)
    {
        if (! $receivers instanceof ShortMessage) {
            $receivers = $this->factory->create($receivers, $body);
        }

        if (is_callable($this->beforeSingleShortMessageCallback)) {
            call_user_func_array($this->beforeSingleShortMessageCallback, [$receivers]);
        }

        $response = $this->client->sendShortMessage($receivers);

        if (is_callable($this->afterSingleShortMessageCallback)) {
            call_user_func_array($this->afterSingleShortMessageCallback, [$response, $receivers]);
        }

        return $response;
    }

    /**
     * Send the given short messages.
     *
     * @param  array|ShortMessageCollection $messages An array containing short message arrays or collection.
     *
     * @return NetgsmResponseInterface The parsed Netgsm response object.
     */
    public function sendShortMessages($messages)
    {
        if (! $messages instanceof ShortMessageCollection) {
            $collection = $this->collectionFactory->create();

            foreach ($messages as $message) {
                $collection->push($this->factory->create(
                    $message['recipient'],
                    $message['message']
                ));
            }

            $messages = $collection;
        }

        if (is_callable($this->beforeMultipleShortMessageCallback)) {
            call_user_func_array($this->beforeMultipleShortMessageCallback, [$messages]);
        }

        $response = $this->client->sendShortMessages($messages);

        if (is_callable($this->afterMultipleShortMessageCallback)) {
            call_user_func_array($this->afterMultipleShortMessageCallback, [$response, $messages]);
        }

        return $response;
    }
}
