<?php

namespace SendyPHP\ValueObject;

class SendyResponse
{
    /**
     * Indicates if the request was successful
     *
     * @var bool $successful
     */
    protected $successful;

    /**
     * The result sended by Sendy
     *
     * @var mixed $result
     */
    protected $result;

    /**
     * Initializes a Sendy Response
     * @param bool $successful Indicates if the request was successful
     * @param mixed $result The original Sendy response
     */
    public function __construct($successful, $result)
    {
        $this->successful = !!$successful;
        $this->result = $result;
    }

    /**
     * Indicates if the request was successful
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Return the original response of Sendy
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
