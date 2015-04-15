<?php

namespace JsonRpc\Spec;

use JsonSerializable;

/**
 * When a rpc call encounters an error,
 * the Response Object MUST contain the error member.
 */
class Error implements JsonSerializable
{
    /**
     * @var integer A Number that indicates the error type that occurred.
     */
    private $code;

    /**
     * @var string A String providing a short description of the error.
     */
    private $message;

    /**
     * @var mixed A Primitive or Structured value that contains additional information about the error.
     * This may be omitted.
     * The value of this member is defined by the Server (e.g. detailed error information, nested errors etc.).
     */
    private $data;

    public function __construct($code, $message, $data = null)
    {
        $this->code = (integer)$code;
        $this->message = (string)$message;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => $this->getData(),
        ];
    }
}
