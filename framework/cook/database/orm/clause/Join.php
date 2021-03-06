<?php

namespace cook\database\orm\clause;

/**
 * Class Join
 */
class Join extends Container {

    /**
     * @param $table
     * @param $first
     * @param null   $operator
     * @param null   $second
     * @param string $joinType
     */
    public function join($table, $first, $operator = null, $second = null, $joinType = 'INNER') {
        $this->container[] = ' ' . $joinType . ' JOIN ' . $table . ' ON ' . $first . ' ' . (in_array($operator, $this->exp) ? $operator : ($this->exp[$operator] ?? '=')) . ' ' . $second;
    }

    /**
     * @param $table
     * @param $first
     * @param null $operator
     * @param null $second
     */
    public function leftJoin($table, $first, $operator = null, $second = null) {
        $this->join($table, $first, $operator, $second, 'LEFT OUTER');
    }

    /**
     * @param $table
     * @param $first
     * @param null $operator
     * @param null $second
     */
    public function rightJoin($table, $first, $operator = null, $second = null) {
        $this->join($table, $first, $operator, $second, 'RIGHT OUTER');
    }

    /**
     * @param $table
     * @param $first
     * @param null $operator
     * @param null $second
     */
    public function fullJoin($table, $first, $operator = null, $second = null) {
        $this->join($table, $first, $operator, $second, 'FULL OUTER');
    }

    /**
     * @return string
     */
    public function __toString() {
        if (empty($this->container)) {
            return '';
        }
        $args = [];
        foreach ($this->container as $join) {
            $args[] = $join;
        }
        $this->container = [];
        return implode('', $args);
    }

}
