<?php    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


namespace Stripe\Util;

use IteratorAggregate;
use ArrayIterator;

class Set implements IteratorAggregate
{
    private $_elts;

    public function __construct($members = [])
    {
        $this->_elts = [];
        foreach ($members as $item) {
            $this->_elts[$item] = true;
        }
    }

    public function includes($elt)
    {
        return isset($this->_elts[$elt]);
    }

    public function add($elt)
    {
        $this->_elts[$elt] = true;
    }

    public function discard($elt)
    {
        unset($this->_elts[$elt]);
    }

    public function toArray()
    {
        return array_keys($this->_elts);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
}
