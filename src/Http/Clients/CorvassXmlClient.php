<?php

namespace BahriCanli\Corvass\Http\Clients;

use BahriCanli\Corvass\ShortMessage;
use BahriCanli\Corvass\ShortMessageCollection;
use BahriCanli\Corvass\Http\Responses\CorvassXmlResponse;
use BahriCanli\Corvass\Http\Responses\CorvassResponseInterface;

/**
 * Class CorvassXmlClient.
 */
class CorvassXmlClient implements CorvassClientInterface
{
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
     * @param string $url
     * @param string $username
     * @param string $password
     * @param string $outboxName
     */
    public function __construct($url, $username, $password, $outboxName)
    {
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
        $payload = $this->generateSingleMessageBody($shortMessage);

        return new CorvassXmlResponse($this->performCurlSession($payload));
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
        $payload = $this->generateMultipleMessageBody($shortMessageCollection);

        return new CorvassXmlResponse($this->performCurlSession($payload));
    }

    /**
     * Generate the xml http request body.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return string
     */
    private function generateSingleMessageBody(ShortMessage $shortMessage)
    {
        $body = '<?xml version="1.0" encoding="iso-8859-9"?>';
        $body .= '<message-context type="smmgsd">';
        $body .= $this->generateAuthTags();
        $body .= $this->generateValidityRangeTags();
        $body .= $shortMessage->toSingleMessageXml();
        $body .= '</message-context>';

        return $body;
    }

    /**
     * Generate the xml http request body.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return string
     */
    private function generateMultipleMessageBody(ShortMessageCollection $shortMessageCollection)
    {
        $body = '<?xml version="1.0" encoding="iso-8859-9"?>';
        $body .= '<message-context type="mmmgsd">';
        $body .= $this->generateAuthTags();
        $body .= $this->generateValidityRangeTags();
        $body .= $shortMessageCollection->toXml();
        $body .= '</message-context>';

        return $body;
    }

    private function generateValidityRangeTags()
    {
        return '<start-date></start-date><expire-date></expire-date>';
    }

    /**
     * Get the auth credentials as xml tags.
     *
     * @return string
     */
    private function generateAuthTags()
    {
        return "<username>{$this->username}</username>"
            . "<password>{$this->password}</password>"
            . "<outbox-name>{$this->outboxName}</outbox-name>";
    }

    /**
     * Perform the curl session.
     *
     * @param  string $payload
     *
     * @return string
     */
    private function performCurlSession($payload)
    {
        $perCurlConnection = curl_init();
        curl_setopt($perCurlConnection, CURLOPT_URL, $this->url);
        curl_setopt($perCurlConnection, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($perCurlConnection, CURLOPT_TIMEOUT, 10);
        curl_setopt($perCurlConnection, CURLOPT_POSTFIELDS, $payload);

        $result = curl_exec($perCurlConnection);

        return $result;
    }
}
