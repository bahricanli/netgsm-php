<?php

namespace BahriCanli\Corvass\Http\Responses;

use BadMethodCallException;

/**
 * Class CorvassHttpApiResponse.
 */
final class CorvassHttpResponse implements CorvassResponseInterface
{
    /**
     * The read response of SMS message request..
     *
     * @var array
     */
    private $responseAttributes = [];

    /**
     * The Corvass error codes.
     *
     * @var array
     */
    private static $statuses = [
        '0' => 'Başarılı İşlem',
        '1' => 'Hatalı apikey / apisecret bilgisi',
        '2' => 'SMS gönderiminde yetersiz kredi / Raporlama: geçersiz msgid, paket işlenmemiş ya da rapor oluşturulmamış',
        '4' => 'Eksik parametre',
        '5' => 'Hatalı parametre',
        '6' => 'Tanımsız orijinatör bilgisi',
    ];

    /**
     * Create a message response.
     *
     * @param  string $responseBody
     */
    public function __construct($responseBody)
    {
        $this->responseAttributes = $this->readResponseBodyString($responseBody);
    }

    /**
     * Determine if the operation was successful or not.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return '0' === $this->statusCode();
    }

    /**
     * Get the status code.
     *
     * @return string
     */
    public function statusCode()
    {
        return (string) $this->responseAttributes['statusCode'];
    }

    /**
     * Get the string representation of the status.
     *
     * @return string
     */
    public function status()
    {
        return array_key_exists($this->statusCode(), self::$statuses)
            ? self::$statuses[$this->statusCode()]
            : 'Unknown';
    }

    /**
     * Get the message of the response.
     *
     * @return null|string
     */
    public function message()
    {
        return null;
    }

    /**
     * Get the group identifier from the response.
     */
    public function groupId()
    {
        throw new BadMethodCallException(
            "Corvass Http API responses do not group bulk message identifiers. Use messageReportIdentifiers instead."
        );
    }

    /**
     * Get the message report identifiers for the messages sent.
     * Message report id returns -1 if invalid Msisdns, -2 if invalid message text.
     *
     * @return array
     */
    public function messageReportIdentifiers()
    {
        if (array_key_exists('messageids', $this->responseAttributes)) {
            return explode('|', $this->responseAttributes['messageids']);
        }

        return [];
    }

    /**
     * Read the message response body string.
     *
     * @param $responseBodyString
     * @return array
     */
    private function readResponseBodyString($responseBodyString)
    {

        $result['status'] = $responseBodyString;

        /*
        $responseLines = array_filter(array_map(function ($value) {
            return trim($value);
        }, explode("\n", $responseBodyString)));

        $result = [];
        foreach ($responseLines as $responseLine) {
            $responseParts = explode('=', $responseLine);
            $result[strtolower($responseParts[0])] = $responseParts[1];
        }
        */

        $status = (int) $result['status'];
        unset($result['status']);
        $result['success'] = ($status >= 0) ? true : false;
        $result['statusCode'] = $status;

        return $result;
    }
}
