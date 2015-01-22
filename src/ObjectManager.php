<?php

namespace JsonRpc;

use JsonRpc\Spec\BatchRequest;
use JsonRpc\Spec\BatchResponse;
use JsonRpc\Spec\Error;
use JsonRpc\Spec\Request;
use JsonRpc\Spec\Response;

class ObjectManager implements ObjectManagerInterface
{
    /**
     * @var Request[]
     */
    private $requests = [];

    /**
     * @var Request[]
     */
    private $notifications = [];

    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var Response[]
     */
    private $responses = [];

    /**
     * @var TransportInterface
     */
    private $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function addRequest($name, $data, $id = null)
    {
        return $this->addRequestInternal($name, $data, $id ? : uniqid($name, true));
    }

    /**
     * Add request without response to queue
     * @param string $name Requests name
     * @param mixed $data
     */
    public function addNotification($name, $data = null)
    {
        $this->addRequestInternal($name, $data);
    }

    /**
     * Return true if response has error, false otherwise
     * @param mixed $id Requests id
     * @return boolean
     */
    public function hasError($id)
    {
        return $this->hasResponse($id) && $this->getResponse($id)->getError() instanceof Error;
    }

    /**
     * Return error message
     * @param mixed $id Requests id
     * @return string
     */
    public function getError($id)
    {
        return $this->getResponse($id)->getError()->getMessage();
    }

    /**
     * Return error code
     * @param mixed $id
     * @return integer
     */
    public function getErrorCode($id)
    {
        return $this->getResponse($id)->getError()->getCode();
    }

    /**
     * Return error data
     * @param mixed $id Requests id
     * @return mixed
     */
    public function getErrorData($id)
    {
        return $this->getResponse($id)->getError()->getData();
    }

    public function getResult($id)
    {
        return $this->getResponse($id)->getResult();
    }

    public function removeRequest($key)
    {
        if (isset($this->requests[$key])) {
            unset($this->requests[$key]);
        }
    }

    public function commit()
    {
        $requests = array_merge($this->requests, $this->notifications);
        if (empty($requests)) {
            return;
        } elseif (count($requests) > 1) {
            $data = new BatchRequest($requests);
        } else {
            $data = reset($requests);
        }

        $result = $this->transport->send($data);
        if ($result instanceof BatchResponse) {
            /** @var Response[] $responses */
            $responses = $result->getArrayCopy();
            foreach ($responses as $response) {
                $this->addResponse($response);
            }
        } elseif ($result instanceof Response) {
            $this->addResponse($result);
        }
        $this->clear();
    }

    public function clear()
    {
        $this->requests =
        $this->notifications =
        $this->keys = [];
    }

    private function hasResponse($id)
    {
        return isset($this->responses[$id]);
    }

    /**
     * @param $id
     * @return Response
     */
    private function getResponse($id)
    {
        if ($this->hasResponse($id)) {
            return $this->responses[$id];
        }
        throw new OutOfBoundsException($id);
    }

    /**
     * @param Response $response
     */
    private function addResponse(Response $response)
    {
        $id = $response->getId();
        if (isset($this->keys[$id])) {
            $this->responses[$this->keys[$id]] = $response;
        }
    }

    /**
     * @param $name
     * @param $data
     * @param null $id
     * @return null|string
     */
    protected function addRequestInternal($name, $data, $id = null)
    {
        $request = new Request($name, $data, $id);
        if ($id !== null) {
            $key = md5(serialize([$id, $data, $name]));
            if (!isset($this->responses[$key])) {
                $this->requests[$key] = $request;
                $this->keys[$id] = $key;
            }
            return $key;
        } else {
            $this->notifications[] = $request;
            return null;
        }
    }
}
