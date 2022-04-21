<?php

namespace BahriCanli\Corvass\Http\Responses;

use BadMethodCallException;

/**
 * Class CorvassXmlResponse.
 */
class CorvassXmlResponse implements CorvassResponseInterface
{
    /**
     * The predefined status codes of the Corvass Xml api.
     *
     * @var array
     */
    protected static $statuses = [
        '0' => 'Başarılı İşlem',
        '1' => 'Genel Hata (detay için description alanına bakınız.)',
        '2' => 'Geçersiz Alfanumerik',
        '3' => 'Mesaj metni alanı boş bırakılmış',
        '4' => 'MSISDN Alanları boş bırakılmış',
        '5' => 'Yetersiz kredi',
        '6' => 'Mesaj gönderimi gerçekleştirilemedi',
        '7' => 'messageArray alanı boş',
        '20' => 'Request limiti aşıldı',
        '21' => 'SMS/DK limit aşıldı, lütfen bekleyiniz',
        '9996' => 'Hatalı / geçersiz istek URL adresi',
        '9997' => 'Hatalı / geçersiz JSON Formatı',
        '9999' => 'Hatalı / geçersiz authentication bilgisi',
    ];

    /**
     * The Corvass Http status code.
     *
     * @var string
     */
    protected $statusCode;

    /**
     * The message of the response if any.
     *
     * @var string|null
     */
    protected $message;

    /**
     * The group identifier.
     *
     * @var string|null
     */
    protected $groupId;

    /**
     * XmlCorvassHttpResponse constructor.
     *
     * @param  string $data
     */
    public function __construct($data)
    {
        $response = explode(" ", $data);
        $this->statusCode = array_shift($response);

        if (! $this->isSuccessful()) {
            $this->message = implode(' ', $response);
        } else {
            $this->groupId =  array_shift($response);
        }
    }

    /**
     * Determine if the operation was successful or not.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->statusCode === '00';
    }

    /**
     * Get the message of the response.
     *
     * @return null|string
     */
    public function message()
    {
        return trim($this->message) ?: null;
    }

    /**
     * Get the group identifier from the response.
     *
     * @return string
     */
    public function groupId()
    {
        return $this->groupId;
    }

    /**
     * Get the message report identifiers for the messages sent.
     * Message report id returns -1 if invalid Msisdns, -2 if invalid message text.
     */
    public function messageReportIdentifiers()
    {
        throw new BadMethodCallException(
            "Corvass XML API responses do not return message identifiers. Use groupId instead."
        );
    }

    /**
     * Get the status code.
     *
     * @return string
     */
    public function statusCode()
    {
        return $this->statusCode;
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
}
