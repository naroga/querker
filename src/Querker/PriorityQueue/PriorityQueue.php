<?php

namespace Querker\PriorityQueue;

/**
 * Class PriorityQueue
 * @package Querker\PriorityQueue
 */
class PriorityQueue implements \Serializable, \Countable
{
    private $data = [];

    public function insert($value, $priority = 1)
    {

        if (!isset($this->data[$priority])) {
            $this->data[$priority] = [$value];
        } else {
            $this->data[$priority][] = $value;
        }
    }

    public function extract()
    {
        if (empty($this->data)) {
            return null;
        }
        $max = max(array_keys($this->data));
        $return = array_shift($this->data[$max]);
        if (empty($this->data[$max])) {
            unset($this->data[$max]);
        }
        return $return;
    }

    public function count()
    {
        $total = 0;
        foreach ($this->data as $index => $value) {
            $total += count($value);
        }
        return $total;
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }
}
