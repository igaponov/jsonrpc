<?php

namespace JsonRpc\Utils;

use JsonRpc\Spec\BatchRequest;
use JsonRpc\Spec\BatchResponse;
use JsonRpc\Spec\Error;
use JsonRpc\Spec\Request;
use JsonRpc\Spec\Response;

class Decoder
{
    const TYPE_RESPONSE = 1;

    const TYPE_REQUEST = 2;

    public static function decode($json)
    {
        $json = trim($json);
        $isBatch = strpos($json, '[') === 0;
        $type = strpos($json, 'method') !== false ? self::TYPE_REQUEST : self::TYPE_RESPONSE;
        $array = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Json decode error', json_last_error());
        }
        if ($isBatch) {
            $return = $type === self::TYPE_REQUEST ? new BatchRequest() : new BatchResponse();
            foreach ($array as $value) {
                $return->append(self::decodeUnit($type, $value));
            }
        } else {
            $return = self::decodeUnit($type, $array);
        }

        return $return;
    }

    private static function decodeUnit($type, $array)
    {
        if ($type === self::TYPE_REQUEST) {
            $array = array_replace([
                'params' => null,
                'id' => null,
            ], $array);
            $return = new Request($array['method'], $array['params'], $array['id']);
        } else {
            $array = array_replace([
                'result' => null,
                'error' => null,
                'id' => null,
            ], $array);
            $error = $array['error'];
            if ($error !== null) {
                $array['error'] = new Error($error['code'], $error['message'], $error['data']);
            }
            $return = new Response($array['result'], $array['error'], $array['id']);
        }
        return $return;
    }
}
