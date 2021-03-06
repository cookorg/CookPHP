<?php

namespace cook\database\orm\statement;

/**
 * 更新类
 */
class Update extends Statement {

    /**
     * @param array $pairs
     * @return $this
     */
    public function set(array $pairs) {
        foreach ($pairs as $column => $value) {
            if (preg_match('/([\w]+)(\[(\+|\-|\*|\/)\])/i', $column, $match) && is_numeric($value)) {
                $this->columns[] = $this->db->name($match[1]) . ' = ' . $this->db->name($match[1]) . ' ' . $match[3] . ' ?';
                $this->values[] = $value;
                continue;
            }
            $this->columns[] = $this->db->name($column) . ' = ?';
            $this->values[] = is_array($value) ? json_encode($value) : $value;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getSql() {
        if (empty($this->table)) {
            trigger_error('没有设置要更新的表', E_USER_ERROR);
        }
        if (empty($this->columns) && empty($this->values)) {
            trigger_error('缺少要更新的列和值', E_USER_ERROR);
        }
        $sql = 'UPDATE ' . $this->table;
        $sql .= ' SET ' . $this->getColumns();
        $sql .= $this->Where;
        $sql .= $this->Order;
        $sql .= $this->Limit;
        return $sql;
    }

    /**
     * @return \PDOstatement
     */
    public function execute() {
        return $this->db->exec($this->getSql(), $this->values)->rowCount();
    }

    /**
     * @return string
     */
    protected function getColumns() {
        return implode(' , ', $this->columns);
    }

}
