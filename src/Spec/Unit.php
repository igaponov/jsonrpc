<?php

namespace JsonRpc\Spec;

abstract class Unit implements UnitInterface
{
    const VERSION = '2.0';

    /**
     * @var string A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     */
    private $jsonRpc;

    /**
     * @var string|integer|null An identifier established by the Client that MUST contain a String, Number, or NULL value if included.
     * If it is not included it is assumed to be a notification.
     * The value SHOULD normally not be Null and Numbers SHOULD NOT contain fractional parts.
     */
    private $id;

    public function __construct($id = null)
    {
        $this->jsonRpc = self::VERSION;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getJsonRpc()
    {
        return $this->jsonRpc;
    }

    /**
     * @return string|integer|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->getSerializableAttributes();
    }

    /**
     * @return array Data which should be serialized
     */
    protected function getSerializableAttributes()
    {
        $attributes = [
            'jsonRpc' => $this->jsonRpc,
        ];
        if ($this->id !== null) {
            $attributes['id'] = $this->id;
        }

        return $attributes;
    }
}
