<?php

namespace JsonRpc;

use JsonRpc\Spec\UnitInterface;

interface TransportInterface
{
    public function send(UnitInterface $data);
}
