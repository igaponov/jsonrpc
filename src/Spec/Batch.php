<?php

namespace JsonRpc\Spec;

use ArrayObject;

/**
 * To send several Request objects at the same time,
 * the Client MAY send an Array filled with Request objects.
 *
 * The Server should respond with an Array containing the corresponding Response objects,
 * after all of the batch Request objects have been processed.
 */
abstract class Batch extends ArrayObject implements UnitInterface
{
    /**
     * @var string Class of array elements
     */
    protected $itemClass = '\JsonRpc\Spec\Unit';

    /**
     * @inheritdoc
     */
    public function __construct(array $input = [], $flags = 0, $iteratorClass = "ArrayIterator")
    {
        array_walk($input, [$this, 'checkElement']);
        parent::__construct($input, $flags, $iteratorClass);
    }

    /**
     * @inheritdoc
     */
    public function exchangeArray($input)
    {
        array_walk($input, [$this, 'checkElement']);
        parent::exchangeArray($input);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($index, $newVal)
    {
        $this->checkElement($newVal);
        parent::offsetSet($index, $newVal);
    }

    /**
     * @param mixed $value Checks value is instance of itemClass
     */
    protected function checkElement($value)
    {
        if (!$value instanceof $this->itemClass) {
            $message = sprintf('%s can only hold objects of %s class.', __CLASS__, $this->itemClass);
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
