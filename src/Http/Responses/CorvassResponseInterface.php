<?php

namespace BahriCanli\netgsm\Http\Responses;

/**
 * Interface netgsmResponseInterface.
 */
interface netgsmResponseInterface
{
    /**
     * Determine if the operation was successful or not.
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * Get the message of the response.
     *
     * @return null|string
     */
    public function message();

    /**
     * Get the status code.
     *
     * @return string
     */
    public function statusCode();

    /**
     * Get the string representation of the status code.
     *
     * @return string
     */
    public function status();
}
