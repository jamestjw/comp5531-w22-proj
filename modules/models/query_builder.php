<?php

require_once(dirname(__FILE__)."/utils.php");
require_once(dirname(__FILE__)."/../../common.php");

class QueryBuilder
{
    protected string $record_class;
    protected array $includes = array();
    protected ?int $limit = null;

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

    public function where(array $attrs)
    {
        $sql = "SELECT * FROM {$this->table_name()}";
        if (!empty($attrs)) {
            $sql_wheres = array();
            foreach ($attrs as $key => $value) {
                if (is_array($value)) {
                    $placeholder = join(", ", array_map(fn ($e) =>"?", $value));
                    array_push($sql_wheres, "$key in ($placeholder)");
                } else {
                    array_push($sql_wheres, "$key = ?");
                }
            }
            $sql .= sprintf(
                " WHERE %s",
                implode(" AND ", $sql_wheres)
            );
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
                switch ($association_type) {
                    case "has_one":
                        $ids = array_map(fn ($o) => $o->id, $res);
                        if (empty($ids)) {
                            break;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where(array($association_foreign_key => $ids));
                        foreach ($res as $r) {
                            $r->$association = current(array_filter($association_res, fn ($o) => $o->$association_foreign_key==$r->id)) ?? null;
                        }
                        break;
                    case "has_many":
                        $ids = array_map(fn ($o) => $o->id, $res);
                        if (empty($ids)) {
                            break;
                        }
                        $association_res = call_user_func($association_class_name."::includes", $sub_association)->where(array($association_foreign_key => $ids));
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
