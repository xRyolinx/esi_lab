<?php
require_once 'BaseModel.php';
class JoinedModel {
    private BaseModel $model;

    public function __construct(BaseModel $model) {
        $this->model = $model;
    }

    public function getColumns()
    {
        return $this->model->getColumns();
    }
    public function getTable() {
        return $this->model->getTable();
    }

    public function leftJoin(string $modelClass, string $primary, string $foreign)
    {
        return $this->model->leftJoin($modelClass, $primary, $foreign, $this->model);
    }
    public function innerJoin(string $modelClass, string $primary, string $foreign)
    {
        return $this->model->innerJoin($modelClass, $primary, $foreign, $this->model);
    }

    public function getAll(array $select = [], array $conditions = [], array $include = [])
    {
        return $this->model->getAll(model: $this->model, select: $select, conditions: $conditions, include: $include);
    }

    public function getAllIndexedBy(string $column, array $select = [], array $conditions = [], array $include = [])
    {
        return $this->model->getAllIndexedBy(column: $column, model: $this->model, select: $select, conditions: $conditions, include: $include);
    }

    public function getUnique(array $select = [], array $conditions = [], array $include = [])
    {
        return $this->model->getUnique(model: $this->model, select: $select, conditions: $conditions, include: $include);
    }
}