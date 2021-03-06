<?php
// Christopher Almeida Neves - 27521979
// James Juan Whei Tan - 40161156
?>
<?php

require_once(dirname(__FILE__)."/utils.php");
require_once(dirname(__FILE__)."/../../common.php");

class QueryBuilder
{
    protected string $record_class;
    protected array $includes = array();
    protected ?int $limit = null;
    protected array $order_by = array();
    protected array $joins = array();
    protected array $joins_raw_sql = array();

    public function __construct(string $record_class_name)
    {
        $this->record_class = $record_class_name;
    }

    // Usage
    // 1. QueryBuilder->includes("user") or QueryBuilder->includes(array("user")) or QueryBuilder->includes(array("user" => []))
    //      This preloads the +user+ association
    // 2. QueryBuilder->includes(array("user" => array("messenges")))) or QueryBuilder->includes(array("user" => "messenges")))
    //      This preloads the +user+ association along with the
    //      +messages+ association that belongs to the +user+
    // TODO: Verify that associations can be nested arbitrarily deeply!
    public function includes($associations): QueryBuilder
    {
        // Check if it is a single association
        if (is_string($associations)) {
            $associations = array($associations);
        }

        // Check if user passed in an associative array
        if (isAssoc($associations)) {
            foreach ($associations as $association => $nested_association) {
                if (!in_array($association, $this->record_class::getAssociations())) {
                    throw new ErrorException("$association is not a valid association in {$this->record_class}");
                }
                $this->includes[$association] = $nested_association;
            }
        } else {
            foreach ($associations as $association) {
                if (!in_array($association, $this->record_class::getAssociations())) {
                    throw new ErrorException("$association is not a valid association in {$this->record_class}");
                }
                $this->includes[$association] = array();
            }
        }

        return $this;
    }

    // Usage
    // QueryBuilder->limit(5).where(["id" => [1,2,3]])
    //      This limits the size of the result set
    public function limit(int $limit): QueryBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    // Usage
    // QueryBuilder->order(["created_at": "desc"]).where(["id" => [1,2,3]])
    //      This orders the result set
    public function order(array $data): QueryBuilder
    {
        $this->order_by = array_merge($this->order_by, $data);
        return $this;
    }

    public function where_raw_sql(string $raw_sql) {
        $sql = "SELECT {$this->table_name()}.* FROM {$this->table_name()}";

        if (!empty($this->joins)) {
            foreach ($this->joins as $association) {
                $association_type = $this->record_class::getAssociationType($association);
                $association_foreign_key = $this->record_class::getAssociationForeignKey($association);
                $association_class_name = $this->record_class::getAssociationClassName($association);
                $association_table_name = $association_class_name::get_table_name();
                switch ($association_type) {
                    case "has_one":
                    case "has_many":
                        $sql .= " JOIN $association_table_name on {$this->table_name()}.id = $association_table_name.$association_foreign_key";
                        break;
                    case "belongs_to":
                        // TODO: Implement this when necessary
                        break;
                }
            }
        }

        if (!empty($this->joins_raw_sql)) {
            foreach ($this->joins_raw_sql as $join_sql) {
                $sql .= " $join_sql";
            }
        }

        $sql .= " WHERE ".$raw_sql;

        foreach ($this->order_by as $key => $val) {
            $sql .= " ORDER BY $key $val";
        }

        if (isset($this->limit)) {
            $sql .= " LIMIT {$this->limit}";
        }

        $res = execute_sql_query($sql);
        $res = array_map([$this->record_class, 'loadRecordFromData'], $res);

        if (empty($res)) {
            return $res;
        }

        // Preload associations
        if (!empty($this->includes)) {
            foreach ($this->includes as $association => $sub_association) {
                $association_type = $this->record_class::getAssociationType($association);
                $association_class_name = $this->record_class::getAssociationClassName($association);
                $association_foreign_key = $this->record_class::getAssociationForeignKey($association);
                $association_polymorphic_type = $this->record_class::getAssociationPolymorphicTypeColumn($association);
                switch ($association_type) {
                    case "has_one":
                        $ids = array_map(fn ($o) => $o->id, $res);
                        if (empty($ids)) {
                            break;
                        }
                        $association_where = array($association_foreign_key => $ids);
                        if ($association_polymorphic_type) {
                            $association_where[$association_polymorphic_type] = $this->record_class;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where($association_where);
                        foreach ($res as $r) {
                            $r->$association = current(array_filter($association_res, fn ($o) => $o->$association_foreign_key==$r->id)) ?? null;
                        }
                        break;
                    case "has_many":
                        $ids = array_map(fn ($o) => $o->id, $res);
                        if (empty($ids)) {
                            break;
                        }
                        $association_where = array($association_foreign_key => $ids);
                        if ($association_polymorphic_type) {
                            $association_where[$association_polymorphic_type] = $this->record_class;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where($association_where);
                        foreach ($res as $r) {
                            $r->$association = array_filter($association_res, fn ($o) => $o->$association_foreign_key==$r->id);
                        }
                        break;
                    case "belongs_to":
                        $foreign_keys = array_unique(array_map(fn ($o) => $o->$association_foreign_key, $res));
                        if (empty($foreign_keys)) {
                            break;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where(array("id" => $foreign_keys));
                        foreach ($res as $r) {
                            $r->$association = current(array_filter($association_res, fn ($o) => $o->id==$r->$association_foreign_key)) ?? null;
                        }
                        break;
                }
            }
        }

        return $res;
    }

    public function where(array $attrs)
    {
        $sql = "SELECT {$this->table_name()}.* FROM {$this->table_name()}";

        if (!empty($this->joins)) {
            foreach ($this->joins as $association) {
                $association_type = $this->record_class::getAssociationType($association);
                $association_foreign_key = $this->record_class::getAssociationForeignKey($association);
                $association_class_name = $this->record_class::getAssociationClassName($association);
                $association_table_name = $association_class_name::get_table_name();
                switch ($association_type) {
                    case "has_one":
                    case "has_many":
                        $sql .= " JOIN $association_table_name on {$this->table_name()}.id = $association_table_name.$association_foreign_key";
                        break;
                    case "belongs_to":
                        // TODO: Implement this when necessary
                        break;
                }
            }
        }

        if (!empty($this->joins_raw_sql)) {
            foreach ($this->joins_raw_sql as $join_sql) {
                $sql .= " $join_sql";
            }
        }

        if (!empty($attrs)) {
            $sql_wheres = array();
            $null_keys = array();
            foreach ($attrs as $key => $value) {
                if (is_bool($value)) {
                    $attrs[$key] = intval($value);
                }

                if (is_array($value)) {
                    $placeholder = join(", ", array_map(fn ($e) =>"?", $value));
                    array_push($sql_wheres, "$key in ($placeholder)");
                } elseif (is_null($value)) {
                    array_push($null_keys, $key);
                    array_push($sql_wheres, "$key IS NULL");
                } else {
                    array_push($sql_wheres, "$key = ?");
                }
            }

            foreach ($null_keys as $k) {
                unset($attrs[$k]);
            }

            $sql .= sprintf(
                " WHERE %s",
                implode(" AND ", $sql_wheres)
            );
        }

        foreach ($this->order_by as $key => $val) {
            $sql .= " ORDER BY $key $val";
        }

        if (isset($this->limit)) {
            $sql .= " LIMIT {$this->limit}";
        }

        $res = execute_sql_query($sql, $attrs);
        $res = array_map([$this->record_class, 'loadRecordFromData'], $res);

        if (empty($res)) {
            return $res;
        }

        // Preload associations
        if (!empty($this->includes)) {
            foreach ($this->includes as $association => $sub_association) {
                $association_type = $this->record_class::getAssociationType($association);
                $association_class_name = $this->record_class::getAssociationClassName($association);
                $association_foreign_key = $this->record_class::getAssociationForeignKey($association);
                $association_polymorphic_type = $this->record_class::getAssociationPolymorphicTypeColumn($association);
                switch ($association_type) {
                    case "has_one":
                        $ids = array_map(fn ($o) => $o->id, $res);
                        if (empty($ids)) {
                            break;
                        }
                        $association_where = array($association_foreign_key => $ids);
                        if ($association_polymorphic_type) {
                            $association_where[$association_polymorphic_type] = $this->record_class;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where($association_where);
                        foreach ($res as $r) {
                            $r->$association = current(array_filter($association_res, fn ($o) => $o->$association_foreign_key==$r->id)) ?? null;
                        }
                        break;
                    case "has_many":
                        $ids = array_map(fn ($o) => $o->id, $res);
                        if (empty($ids)) {
                            break;
                        }
                        $association_where = array($association_foreign_key => $ids);
                        if ($association_polymorphic_type) {
                            $association_where[$association_polymorphic_type] = $this->record_class;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where($association_where);
                        foreach ($res as $r) {
                            $r->$association = array_filter($association_res, fn ($o) => $o->$association_foreign_key==$r->id);
                        }
                        break;
                    case "belongs_to":
                        $foreign_keys = array_unique(array_map(fn ($o) => $o->$association_foreign_key, $res));
                        if (empty($foreign_keys)) {
                            break;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where(array("id" => $foreign_keys));
                        foreach ($res as $r) {
                            $r->$association = current(array_filter($association_res, fn ($o) => $o->id==$r->$association_foreign_key)) ?? null;
                        }
                        break;
                }
            }
        }

        return $res;
    }

    public function joins(array $assocs) 
    {
        $this->joins = array_merge($this->joins, $assocs);
        $this->joins = array_unique($this->joins);
        return $this;
    }

    public function joins_raw_sql(string $sql)
    {
        array_push($this->joins_raw_sql, $sql);
        return $this;
    }

    public function find_by(array $attrs)
    {
        return $this->limit(1)->where($attrs)[0] ?? null;
    }

    public function getAll()
    {
        return $this->where(array());
    }

    public function __call($name, $arguments)
    {
        if (startsWith($name, "find_by_")) {
            preg_match('/find_by_(\w+)/', $name, $match);
            return $this->find_by(array($match[1]=>$arguments[0]));
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined function via __call(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );
    }

    private function table_name(): string
    {
        return $this->record_class::get_table_name();
    }
}
