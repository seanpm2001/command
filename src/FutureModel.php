<?php
namespace GuzzleHttp\Command;

use GuzzleHttp\Ring\MagicFutureTrait;
use GuzzleHttp\Ring\Core;
use GuzzleHttp\HasDataTrait;
use GuzzleHttp\ToArrayInterface;
use GuzzleHttp\Utils as GuzzleUtils;

/**
 * Future model result that may not have finished.
 */
class FutureModel implements FutureModelInterface
{
    use MagicFutureTrait;

    public function hasKey($name)
    {
        return isset($this->result[$name]);
    }

    public function get($name)
    {
        return $this->result[$name];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->result);
    }

    public function offsetGet($offset)
    {
        return isset($this->result[$offset]) ? $this->result[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->result[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->result[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->result[$offset]);
    }

    public function toArray()
    {
        return $this->result;
    }

    public function count()
    {
        return count($this->result);
    }

    public function getPath($path)
    {
        return GuzzleUtils::getPath($this->result, $path);
    }

    public function setPath($path, $value)
    {
        GuzzleUtils::setPath($this->result, $path, $value);
    }

    protected function processResult($result)
    {
        if ($result instanceof ToArrayInterface) {
            return $result->toArray();
        }

        if (is_array($result)) {
            return $result;
        }

        throw new \RuntimeException('Future result must be an array. or '
            . 'instance of GuzzleHttp\ToArrayInterface. Found '
            . Core::describeType($result));
    }
}
