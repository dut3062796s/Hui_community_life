<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/9
 * Time: 11:52
 */
class TestModel
{
    /**
     * Add two operands
     * @param interge
     * @return interge
     */
    public function add($a, $b) {
        //return $this->_add($a, $b);
        return $a + $b;
    }

    /**
     * Sub
     */
    public function sub($a, $b) {
        return $a - $b;
    }

    /**
     * Mul
     */
    public function mul($a, $b) {
        return $a * $b;
    }

    /**
     * Protected methods will not be exposed
     * @param interge
     * @return interge
     */
    protected function _add($a, $b) {
        return $a + $b;
    }
}

