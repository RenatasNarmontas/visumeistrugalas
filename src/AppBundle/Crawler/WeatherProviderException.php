<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 16/04/16
 * Time: 21:25
 */

namespace AppBundle\Crawler;

use Exception;

class WeatherProviderException extends Exception
{
    /**
     * WeatherProviderException constructor.
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}