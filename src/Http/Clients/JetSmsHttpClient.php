<?php

namespace BahriCanli\Corvass\Http\Clients;

use GuzzleHttp\Client;
use BahriCanli\Corvass\ShortMessage;
use BahriCanli\Corvass\ShortMessageCollection;
use BahriCanli\Corvass\Http\Responses\CorvassHttpResponse;
use BahriCanli\Corvass\Http\Responses\CorvassResponseInterface;

/**
 * Class CorvassHttpClient.
 */
class CorvassHttpClient implements CorvassClientInterface
{
    /**
     * The Http client.
     *
     * @var Client
     */
    private $httpClient;

    /**
     * The Corvass xml request url.
     *
     * @var string
     */
    private $url;

    /**
     * The auth username.
     *
     * @var string
     */
    private $username;

    /**
     * The auth password.
     *
     * @var string
     */
    private $password;

    /**
     * The outbox name.
     *
     * @var string
     */
    private $outboxName;

    /**
     * XmlCorvassClient constructor.
     *
     * @param Client $client
     * @param string $url
     * @param string $username
     * @param string $password
     * @param string $outboxName
     */
    public function __construct(Client $client, $url, $username, $password, $outboxName)
    {
        $this->httpClient = $client;
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
        $this->outboxName = $outboxName;
    }

    /**
     * Send a short message using the Corvass services.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return CorvassResponseInterface
     */
    public function sendShortMessage(ShortMessage $shortMessage)
    {
        $guzzleResponse = $this->httpClient->request('POST', $this->url, [
            'form_params' => array_merge(
                $shortMessage->toArray(),
                $this->getSendDate(),
                $this->getCredentials()
            ),
        ]);

        return new CorvassHttpResponse((string) $guzzleResponse->getBody());
    }

    /**
     * Send multiple short messages using the Corvass services.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return CorvassResponseInterface
     */
    public function sendShortMessages(ShortMessageCollection $shortMessageCollection)
    {
        $guzzleResponse = $this->httpClient->request('POST', $this->url, [
            'form_params' => array_merge(
                $shortMessageCollection->toArray(),
                $this->getSendDate(),
                $this->getCredentials()
            ),
        ]);

        return new CorvassHttpResponse((string) $guzzleResponse->getBody());
    }

    /**
     * Get the send date of the contents.
     *
     * @return array
     */
    private function getSendDate()
    {
        return [
            'SendDate' => null,
        ];
    }

    /**
     * Get the auth credentials array.
     *
     * @return array
     */
    private function getCredentials()
    {
        return [
            'Username'       => $this->username,
            'Password'       => $this->password,
            'TransmissionID' => $this->outboxName,
        ];
    }
}
