<?php

namespace JsonRpc;

interface ObjectManagerInterface
{
    /**
     * Add request to queue
     * @param string $name Requests name
     * @param mixed $data Requests body
     * @param mixed $id Request id
     * @return string Requests id
     */
    public function addRequest($name, $data, $id = null);

    /**
     * Add request without response to queue
     * @param string $name Requests name
     * @param mixed $data
     */
    public function addNotification($name, $data);

    /**
     * Return true if response has error, false otherwise
     * @param mixed $id Requests id
     * @return boolean
     */
    public function hasError($id);

    /**
     * Return error message
     * @param mixed $id Requests id
     * @return string
     */
    public function getError($id);

    /**
     * Return error code
     * @param mixed $id
     * @return integer
     */
    public function getErrorCode($id);

    /**
     * Return error data
     * @param mixed $id Requests id
     * @return mixed
     */
    public function getErrorData($id);

    /**
     * Return response by request id
     * @param mixed $id
     * @return mixed
     */
    public function getResult($id);

    /**
     * Remove request from queue
     * @param $key
     * @return
     * @internal param Request $request
     */
    public function removeRequest($key);

    /**
     * Commit request queue
     * @param array $options Options for transport
     * @return
     */
    public function commit($options = []);

    /**
     * Clear request queue
     */
    public function clear();
}
