<?php
require_once __DIR__ . '/../config/Database.php';
require_once 'JoinedModel.php';

abstract class BaseModel
{
    protected static $table = "";
    protected static $columns = [];
    protected static $relations = [];
    protected static $specific_relations = [];
    protected static $fillable = [];
    protected static $hidden = [];
    public static $pk = "";

    // if joined
    protected $joins = [
        "joined" => false,
        "table" => null,
        "columns" => [],
    ];

    // ----------------- instance methodes -----------------
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function save()
    {
        $data = $this->toArray();
        if (isset($data[static::$pk])) {
            return static::edit($data, static::$pk, $this->{static::$pk});
        } else {
            $newData = static::create($data);
            $this->{static::$pk} = $newData[static::$pk];
            return true;
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    // necessaires pour join imbriqué
    public function getTable(): string
    {
        if (empty($this->joins['table'])) {
            return static::$table;
        }
        return $this->joins['table'];
    }
    public function getColumns(): array
    {
        if (empty($this->joins['columns'])) {
            return static::$columns;
        }
        return $this->joins['columns'];
    }


    // ------------------ join methode ------------------
    protected static function join(string $TYPE, string $modelClass, string $primary, string $foreign, $model = null)
    {
        $instance = $model ?? new static([]);
        $instance->joins['joined'] = true;

        // import model joined
        require_once "$modelClass.php";

        // changer le FROM
        $tableSelf = $instance->getTable();
        $tableJoin = $modelClass::$table;

        // join statement added to table
        $new_table = '';
        if ($model)
            $new_table = "$tableSelf $TYPE JOIN $tableJoin ON $primary = $tableJoin.$foreign";
        else
            $new_table = "$tableSelf $TYPE JOIN $tableJoin ON $tableSelf.$primary = $tableJoin.$foreign";
        $instance->joins['table'] = $new_table;

        // fusionner les colonnes
        $colonnes = [];
        foreach ($instance->getColumns() as $col) {
            $colonnes[] = $col;
        }
        foreach ($modelClass::$columns as $col) {
            $colonnes[] = $col;
        }
        $instance->joins['columns'] = $colonnes;

        // create joined model instance
        $joinedModel = new JoinedModel($instance);
        return $joinedModel;
    }
    public static function innerJoin(string $modelClass, string $primary, string $foreign, $model = null)
    {
        return static::join('INNER', $modelClass, $primary, $foreign, $model);
    }
    public static function leftJoin(string $modelClass, string $primary, string $foreign, $model = null)
    {
        return static::join('LEFT', $modelClass, $primary, $foreign, $model);
    }

    //  ----------------- static methodes -----------------
    public static function fromArray(array $data)
    {
        return new static($data);
    }

    protected static function db()
    {
        return Database::db();
    }

    public static function create(array $data)
    {
        $cleaned_data = static::checkRequiredFields($data, static::$fillable);
        $db = self::db();
        $columns = implode(", ", static::$fillable);
        $values = implode(", ", array_fill(0, count(static::$fillable), "?"));
        $stmt = $db->prepare("INSERT INTO " . static::$table . " ($columns) VALUES ($values)");
        $stmt->execute(array_values($cleaned_data));

        $cleaned_data[static::$pk] = $db->lastInsertId();
        return $cleaned_data;
    }

    public static function getAll($model = null, array $select = [], array $conditions = [], array $include = [])
    {
        $db = static::db();

        $selectClause = static::getSelectClause($select);
        [$whereClause, $params] = static::getWhereClause($conditions);

        // if joined
        $table = $model ? $model->getTable() : static::$table;

        $sql = "SELECT $selectClause FROM $table $whereClause";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // if joined, return raw data
        if ($model && $model->joins['joined']) {
            return $data;
        }

        // Load relations if needed
        $final_data = static::loadRelations($data, $include);

        return $final_data;
    }

    public static function getUnique($model = null, array $select = [], array $conditions = [], array $include = [])
    {
        $results = static::getAll(model: $model, select: $select, conditions: $conditions, include: $include);
        return count($results) > 0 ? $results[0] : null;
    }

    public static function getAllIndexedBy(string $column, $model = null, array $select = [], array $conditions = [], array $include = [])
    {
        // get cols
        $columns = $model ? $model->getColumns() : static::$columns;

        // check column valid
        if (!in_array($column, $columns)) {
            throw new Exception("Invalid column: $column");
        }

        // get all
        $rows = static::getAll(model: $model, select: $select, conditions: $conditions, include: $include);

        // index by column
        $indexed = [];
        foreach ($rows as $row) {
            if (isset($row[$column])) {
                $indexed[$row[$column]][] = $row;
            }
        }

        return $indexed;
    }


    public static function delete(array $conditions)
    {
        $db = self::db();

        [$whereClause, $params] = static::getWhereClause($conditions);
        $stmt = $db->prepare("DELETE FROM " . static::$table . " " . $whereClause);
        return $stmt->execute($params);
    }

    public static function edit(array $data, $column, $value)
    {
        $db = static::db();

        // clean data without pk if sent
        $cleaned_data = static::checkFields($data);
        unset($cleaned_data[static::$pk]);

        if (empty($cleaned_data)) {
            return;
        }

        // col = ?, col = ?
        $setClause = implode(
            ", ",
            array_map(fn($col) => "$col = ?", array_keys($cleaned_data))
        );

        $stmt = $db->prepare(
            "UPDATE " . static::$table .
            " SET $setClause WHERE " . $column . " = ?"
        );

        // vals + pk
        return $stmt->execute([...array_values($cleaned_data), $value]);
    }

    // ----------------- helper methodes -----------------
    protected static function checkRequiredFields(array $data, array $fields)
    {
        $arr = [];
        foreach ($fields as $field) {
            $arr[$field] = (array_key_exists($field, $data))
                ? $data[$field]
                : null;
        }
        return $arr;
    }

    protected static function checkFields(array $data)
    {
        $arr = [];
        foreach (static::$columns as $field) {
            if (array_key_exists($field, $data)) {
                $arr[$field] = $data[$field];
            }
        }
        return $arr;
    }

    public static function hide(array $data)
    {
        foreach (static::$hidden as $field) {
            if (array_key_exists($field, $data)) {
                unset($data[$field]);
            }
        }
        return $data;
    }

    protected static function loadRelations(array &$rows, array $include): array
    {
        if (empty($include) || empty($rows))
            return $rows;

        foreach ($include as $relationName => $subInclude) {
            // sans include imbriqué
            if (is_int($relationName)) { //car key sera l'index dans le tab
                $relationName = $subInclude;
                $subInclude = [];
            }

            if (
                !isset(static::$relations[$relationName])
                && !in_array($relationName, static::$specific_relations)
            ) {
                throw new Exception("Relation $relationName not defined in " . static::class);
            }

            // gérer les relations spécifiques dans le modèle enfant
            if (in_array($relationName, static::$specific_relations)) {
                continue;
            }

            $relation = static::$relations[$relationName];

            // adapter au type de relation
            switch ($relation['type']) {
                case 'one-to-many':
                    $rows = static::loadOneToMany($rows, $relationName, $relation, $subInclude);
                    break;

                case 'many-to-one':
                    $rows = static::loadManyToOne($rows, $relationName, $relation, $subInclude);
                    break;

                case 'many-to-many':
                    $rows = static::loadManyToMany($rows, $relationName, $relation, $subInclude);
                    break;
            }
        }

        return $rows;
    }

    protected static function loadOneToMany(array $rows, string $relationName, array $relation, array $subInclude): array
    {
        $selfKey = $relation['self_key'];
        $relatedModel = $relation['related_model'];
        $relatedKey = $relation['related_key'];

        $ids = array_column($rows, $selfKey);
        if (empty($ids))
            return $rows;

        require_once "$relatedModel.php";

        // récupérer tous les enfants en 1 requête indexés par self_key
        $children = $relatedModel::getAllIndexedBy(column: $relatedKey, conditions: [
            [$relatedKey => ['comparaison' => 'IN', 'valeur' => $ids]]
        ]);

        // assigner aux parents
        foreach ($rows as &$row) {
            $row[$relationName] = &$children[$row[$selfKey]] ?? [];
        }

        // include imbriqué
        if (!empty($subInclude)) {
            // créer tableau de refs au lieu de val
            $childRefs = [];
            foreach ($rows as &$row) {
                foreach ($row[$relationName] as &$child) {
                    $childRefs[] = &$child;
                }
            }

            // charger les sous-relations
            if (!empty($childRefs)) {
                $relatedModel::loadRelations($childRefs, $subInclude);
            }
        }

        return $rows;
    }

    protected static function loadManyToOne(array $rows, string $relationName, array $relation, array $subInclude): array
    {
        $selfKey = $relation['self_key'];
        $relatedModel = $relation['related_model'];
        $relatedKey = $relation['related_key'];

        $ids = array_unique(array_column($rows, $selfKey));
        if (empty($ids))
            return $rows;

        require_once "$relatedModel.php";

        // récupérer tous les parents en 1 requête, indexé par related_key
        $parents = $relatedModel::getAllIndexedBy(column: $relatedKey, conditions: [
            [$relatedKey => ['comparaison' => 'IN', 'valeur' => $ids]]
        ]);

        // assigner
        foreach ($rows as &$row) {
            // many-to-one donc 1 seul parent
            $row[$relationName] = $parents[$row[$selfKey]][0] ?? null;
        }

        // sous-relations imbriquées
        if (!empty($subInclude)) {
            $parentRefs = [];
            foreach ($rows as &$row) {
                if ($row[$relationName]) {
                    $parentRefs[] = &$row[$relationName];
                }
            }

            if (!empty($parentRefs)) {
                $relatedModel::loadRelations($parentRefs, $subInclude);
            }
        }

        return $rows;
    }

    protected static function loadManyToMany(array $rows, string $relationName, array $relation, array $subInclude): array
    {
        $pivotTable = $relation['table'];
        $selfKey = $relation['self_key'];
        $relatedKey = $relation['related_key'];
        $relatedModel = $relation['related_model'];

        require_once "$relatedModel.php";

        // table
        $table = "$pivotTable LEFT JOIN $relatedModel ON $pivotTable.$relatedKey = $relatedModel." . $relatedModel::$pk;

        // filter only needed rows
        $ids = array_column($rows, $selfKey);
        if (empty($ids))
            return $rows;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $cond = "$pivotTable.$selfKey IN ($placeholders)";

        // get all
        $db = static::db();
        $stmt = $db->prepare("SELECT * FROM $table WHERE $cond");
        $stmt->execute($ids);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // indexer
        $indexed = [];
        foreach ($results as $row) {
            if (isset($row[$selfKey])) {
                $indexed[$row[$selfKey]][] = $row;
            }
        }

        // assigner aux parents
        foreach ($rows as &$row) {
            $row[$relationName] = $indexed[$row[$selfKey]] ?? [];
        }

        // sous-relations imbriquées
        if (!empty($subInclude)) {
            $childRefs = [];
            foreach ($rows as &$row) {
                foreach ($row[$relationName] as &$child) {
                    $childRefs[] = &$child;
                }
            }

            if (!empty($childRefs)) {
                $relatedModel::loadRelations($childRefs, $subInclude);
            }
        }

        return $rows;
    }






    protected static function getSelectClause(array $select)
    {
        if (empty($select)) {
            return '*';
        }
        $validColumns = array_intersect($select, static::$columns);
        return implode(', ', $validColumns);
    }

    protected static function getWhereClause(array $conditions)
    {
        // si on reçoit un AND simple, on normalise
        if (!empty($conditions) && !isset($conditions[0])) {
            $conditions = [$conditions];
        }

        $operations = ['=', '!=', '<', '>', '<=', '>=', 'LIKE', 'IS NULL', 'IS NOT NULL', 'IN', 'NOT IN'];

        $whereClause = '';
        $params = [];

        if (!empty($conditions)) {
            $orGroups = [];

            // CHAQUE OR
            foreach ($conditions as $i => $group) {
                $andConds = [];

                // CHAQUE AND dans le OR
                foreach ($group as $field => $config) {
                    if (!in_array($field, static::$columns)) {
                        throw new Exception("Invalid column: $field");
                    }

                    $op = strtoupper($config['comparaison'] ?? '=');

                    if (is_array($config)) {
                        $value = $config['valeur'] ?? null;
                    }
                    // if flat
                    else {
                        $value = $config;
                    }


                    if (!in_array($op, $operations)) {
                        throw new Exception("Invalid operator: $op");
                    }

                    // NULL op doesn't need value
                    if (in_array($op, ['IS NULL', 'IS NOT NULL'])) {
                        $andConds[] = "$field $op";
                    }
                    // IN / NOT IN
                    elseif (in_array($op, ['IN', 'NOT IN'])) {
                        if (!is_array($value)) {
                            throw new Exception("$op requires an array of values");
                        }
                        if (empty($value)) {
                            // empty IN clause, always false
                            $andConds[] = ($op === 'IN') ? '0=1' : '1=1';
                            continue;
                        }
                        $placeholders = [];
                        foreach ($value as $j => $v) {
                            $paramName = ":{$field}_{$i}_{$j}";
                            $placeholders[] = $paramName;
                            $params[$paramName] = $v;
                        }
                        $andConds[] = "$field $op (" . implode(', ', $placeholders) . ")";
                    } else {
                        $paramName = ":{$field}_{$i}";
                        $andConds[] = "$field $op $paramName";
                        $params[$paramName] = ($op === 'LIKE') ? "%$value%" : $value;
                    }
                }

                if (!empty($andConds)) {
                    $orGroups[] = '(' . implode(' AND ', $andConds) . ')';
                }
            }

            if (!empty($orGroups)) {
                $whereClause = ' WHERE ' . implode(' OR ', $orGroups);
            }
        }

        return [$whereClause, $params];
    }

}