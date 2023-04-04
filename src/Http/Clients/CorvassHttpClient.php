<?php

namespace BahriCanli\netgsm\Http\Clients;

use GuzzleHttp\Client;
use BahriCanli\netgsm\ShortMessage;
use BahriCanli\netgsm\ShortMessageCollection;
use BahriCanli\netgsm\Http\Responses\netgsmHttpResponse;
use BahriCanli\netgsm\Http\Responses\netgsmResponseInterface;

/**
 * Class netgsmHttpClient.
 */
class netgsmHttpClient implements netgsmClientInterface
{
    /**
     * The Http client.
     *
     * @var Client
     */
    private $httpClient;

    /**
     * The netgsm xml request url.
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
     * XmlnetgsmClient constructor.
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
     * Send a short message using the netgsm services.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return netgsmResponseInterface
     */
    public function sendShortMessage(ShortMessage $shortMessage)
    {

        $guzzleResponse = $this->httpClient->request('POST', $this->url,  [
            'form_params' => array_merge(
                $shortMessage->toArray(),
                $this->getExtraPramaters(),
                $this->getCredentials()
            ),
        ]);

        return new netgsmHttpResponse((string) $guzzleResponse->getBody());
    }

    /**
     * Send multiple short messages using the netgsm services.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return netgsmResponseInterface
     */
    public function sendShortMessages(ShortMessageCollection $shortMessageCollection)
    {
        $guzzleResponse = $this->httpClient->request('POST', $this->url, [
            'form_params' => array_merge(
                $shortMessageCollection->toArray(),
                $this->getExtraPramaters(),
                $this->getCredentials(),
            ),
        ]);

        return new netgsmHttpResponse((string) $guzzleResponse->getBody());
    }

    /**
     * Get the extra parameters of the contents.
     *
     * @return array
     */
    private function getExtraPramaters()
    {
        return [
            'action' => 0,
            'messageType' => 'B',
            'recipientType' => 'TACIR',
        ];
    }

    /**
     * Get the send date of the contents.
     *
     * @return array
     */
    private function getSendDate()
    {
        return [
            'sDate' => null,
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
            'username'       => $this->username,
            'password'       => $this->password,
            'originator'     => $this->outboxName,
        ];
    }
}
