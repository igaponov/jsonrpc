<?php

namespace JsonRpc\Spec;

/**
 * When a rpc call is made, the Server MUST reply with a Response,
 * except for in the case of Notifications.
 */
class Response extends Unit
{
    /**
     * @var mixed The value of this member is determined by the method invoked on the Server.
     * This member is REQUIRED on success.
     * This member MUST NOT exist if there was an error invoking the method.
     */
    private $result;

    /**
     * @var Error
     * This member is REQUIRED on error.
     * This member MUST NOT exist if there was no error triggered during invocation.
     */
    private $error;

    public function __construct($result = null, Error $error = null, $id = null)
    {
        if ($result === null && $error === null) {
            throw new \LogicException('Result or error must be defined.');
        }
        parent::__construct($id);
        $this->result = $result;
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return Error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return int|null
     */
    public function getErrorCode()
    {
        if ($this->error instanceof Error) {
            return $this->error->getCode();
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        if ($this->error instanceof Error) {
            return $this->error->getMessage();
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getErrorData()
    {
        if ($this->error instanceof Error) {
            return $this->error->getData();
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getSerializableAttributes()
    {
        $attributes = parent::getSerializableAttributes();
        if ($this->error !== null) {
            $attributes['error'] = $this->error;
        } else {
            $attributes['result'] = $this->result;
        }

        return $attributes;
    }
}
