<?php

namespace BahriCanli\Netgsm\Http\Clients;

use BahriCanli\Netgsm\ShortMessage;
use BahriCanli\Netgsm\ShortMessageCollection;
use BahriCanli\Netgsm\Http\Responses\NetgsmXmlResponse;
use BahriCanli\Netgsm\Http\Responses\NetgsmResponseInterface;

/**
 * Class NetgsmXmlClient.
 */
class NetgsmXmlClient implements NetgsmClientInterface
{
    /**
     * The Netgsm xml request url.
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
     * XmlNetgsmClient constructor.
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
     * Send a short message using the Netgsm services.
     *
     * @param  ShortMessage $shortMessage
     *
     * @return NetgsmResponseInterface
     */
    public function sendShortMessage(ShortMessage $shortMessage)
    {
        $payload = $this->generateSingleMessageBody($shortMessage);

        return new NetgsmXmlResponse($this->performCurlSession($payload));
    }

    /**
     * Send multiple short messages using the Netgsm services.
     *
     * @param  ShortMessageCollection $shortMessageCollection
     *
     * @return NetgsmResponseInterface
     */
    public function sendShortMessages(ShortMessageCollection $shortMessageCollection)
    {
        $payload = $this->generateMultipleMessageBody($shortMessageCollection);

        return new NetgsmXmlResponse($this->performCurlSession($payload));
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
        $body = '<?xml version="1.0" encoding="UTF-8"?>';
        $body .= '<mainbody>';
        $body .= $this->generateAuthTags();
        $body .= $shortMessage->toSingleMessageXml();
        $body .= '</mainbody>';
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
        $body = "<?xml version='1.0' encoding='UTF-8'?>";
        $body .= "<mainbody>";
        $body .= $this->generateAuthTags();
        $body .= $shortMessageCollection->toXml();
        $body .= "</mainbody>";

        return $body;
    }

    /**
     * Get the auth credentials as xml tags.
     *
     * @return string
     */
    private function generateAuthTags()
    {

        return "<header>"
            . "<company dil='TR'>Netgsm</company>"
            . "<usercode>{$this->username}</usercode>"
            . "<password>{$this->password}</password>"
            . "<msgheader>{$this->outboxName}</msgheader>"
            . "<type>1:n</type>"
            . "</header>";
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
