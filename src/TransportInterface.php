<?php

namespace JsonRpc;

use JsonRpc\Spec\BatchResponse;
use JsonRpc\Spec\Response;
use JsonRpc\Spec\UnitInterface;

interface TransportInterface
{
    /**
     * Send data to transport layer
     *
     * @param UnitInterface $data
     * @param array $options
     * @return Response|BatchResponse
     */
    public function send(UnitInterface $data, $options = []);
}
