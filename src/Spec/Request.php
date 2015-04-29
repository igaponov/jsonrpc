<?php

namespace JsonRpc\Spec;

/**
 * A rpc call is represented by sending a Request object to a Server.
 */
class Request extends Unit implements RequestInterface
{
    /**
     * @var string A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period character (U+002E or ASCII 46)
     * are reserved for rpc-internal methods and extensions and MUST NOT be used for anything else.
     */
    private $method;

    /**
     * @var mixed A Structured value that holds the parameter values to be used during the invocation of the method.
     * This member MAY be omitted.
     */
    private $params;

    public function __construct($method, $params = null, $id = null)
    {
        parent::__construct($id);
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     */
    protected function getSerializableAttributes()
    {
        $attributes = parent::getSerializableAttributes();
        $attributes['method'] = $this->method;
        if ($this->params !== null) {
            $attributes['params'] = $this->params;
        }

        return $attributes;
    }
}
