<?php

namespace Infrastructure\Database\Eloquent;

use Illuminate\Support\Facades\DB;
use Ninhtqse\Genie\Repository as BaseRepository;
use Infrastructure\Exceptions as IncException;

abstract class Repository extends BaseRepository
{
    public $dirty = [];
    public $originalData;

    public function create(array $data)
    {
        $model = $this->getModel();

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function update($model, array $data)
    {
        $model->fill($data);
        $this->originalData = $model->getOriginal();
        $this->dirty = $model->getDirty();
        $model->save();
        return $model;
    }

    public function updateAndGetInfo($model, array $data)
    {
        $model->fill($data);
        $originalData = $model->getOriginal();
        $dirtyData    = $model->getDirty();
        $model->save();
        $model->setAttribute('original', $originalData);
        $model->setAttribute('dirty', $dirtyData);
        return $model;
    }

    // create not using mass assignment
    public function createNotUsingMassAssignment(array $data)
    {
        $invoice = $this->getModel();

        foreach ($data as $key => $value) {
            $invoice->{$key} = $value;
        }
        $invoice->save();
        return $invoice;
    }

    // update not using mass assignment
    public function updateNotUsingMassAssignment($model, array $data)
    {
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }
        $model->save();

        return $model;
    }

    public function getDataChanged($attribute = [])
    {
        $dataChange = '';
        if ($this->dirty) {
            $dataChange = 'Nội dung thay đổi: ' . '</br>';
            foreach ($this->dirty as $key => $value) {
                $this->originalData[$key] = (empty($this->originalData[$key])) ? '' : $this->originalData[$key];
                if ($key == 'json_data') {
                    $json_old = '{"';
                    if ($this->originalData[$key]) {
                        foreach ($this->originalData[$key] as $key1 => $value1) {
                            $json_old .= $key1 . '":"' . $value1 ;
                        }
                    }
                    $json_old .= '"}';
                    $dataChange .= $key . ': ' . $json_old . ' => ' . $value . ', ';
                } else {
                    $field = $key;
                    if ($attribute) {
                        $k = $attribute['name'] . '.' . $key;
                        $field = @$attribute['message'][$k];
                    }
                    $dataChange .= $field . ': ' . $this->originalData[$key] . ' => ' . $value ;
                }
                $dataChange .= '</br>';
            }
        }
        return trim($dataChange, '</br>');
    }

    /**
     * Get resources by a where clause
     * @param  string $column
     * @param  mixed $value
     * @param  array $options
     * @return Collection
     */
    public function getWhereWithPagination($column, $value, $tag = 'item', array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->where($column, $value);
        $queryCount = clone $query;
        $total = $queryCount->offset(0)->limit(PHP_INT_MAX)->count();
        $meta = ['total' => $total];
        return [
            'meta' => $meta,
            $tag   => $query->get(),
        ];
    }

    public function getWhereArrayWithPagination(array $clauses, $tag = 'item', array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->where($clauses);
        $queryCount = clone $query;
        $total = $queryCount->offset(0)->limit(PHP_INT_MAX)->count();
        $meta = ['total' => $total];
        return [
            'meta' => $meta,
            $tag   => $query->get(),
        ];
    }

    public function getPagination($tag = 'item', array $options = [])
    {
        $query = $this->createBaseBuilder($options);
        $queryCount = clone $query;
        $total = $queryCount->offset(0)->limit(PHP_INT_MAX)->count();
        $meta = ['total' => $total];
        return [
            'meta' => $meta,
            $tag   => $query->get(),
        ];
    }

    public function getWhereRaw($params, array $options = [])
    {
        $query = $this->createBaseBuilder($options);

        $query->whereRaw($params);

        return $query->get();
    }

    //============> SUPPORT TRANSLATIONS <============
    public function createTranslations($array = [])
    {
        [$tabelName,$foreign_key] = $this->getTableNameTranslation();
        $array['id'] = uuid();
        if ($array) {
            $object = @$array[$tabelName];
            unset($array[$tabelName]);
            if (is_array($object) && count($object) > 0) {
                foreach ($object as $item) {
                    $item['id'] = uuid();
                    $item[$foreign_key] = $array['id'];
                    DB::table($tabelName)->insert($item);
                }
            }
        } elseif (!$array[$tabelName]) {
            $array[$tabelName] = 1;
            unset($array[$tabelName]);
        }
        $model = $this->getModel()->create($array);
        return $model;
    }

    public function updateTranslations($model, $data)
    {
        if ($data) {
            [$tabelName,$foreign_key] = $this->getTableNameTranslation();

            if ($model == false) {
                $data['id'] = uuid();
            }
            if (!empty($data[$tabelName])) {
                $object = $data[$tabelName];
                unset($data[$tabelName]);
                //remove translation
                if ($model) {
                    $model->$tabelName()->delete();
                }
                foreach ($object as $item) {
                    $item['id'] = uuid();
                    $item[$foreign_key] = ($model) ? $model->id : $data['id'];
                    DB::table($tabelName)->insert($item);
                }
            }
        }
        if ($data && $model) {
            return $this->update($model, $data);
        } elseif (!$model && $data) {
            return $this->create($data);
        }
        return $model;
    }

    public function deleteTranslations($model)
    {
        [$tabelName,$foreign_key] = $this->getTableNameTranslation();
        $model->$tabelName()->delete();
        $model->delete();
    }

    public function deleteWhereInTranslation($array)
    {
        [$tabelName,$foreign_key] = $this->getTableNameTranslation();
        DB::table($tabelName)->whereIn($foreign_key, $array)->delete();
        $this->getModel()->whereIn('id', $array)->delete();
    }

    public function getTableNameTranslation()
    {
        if (@$this->translation && @$this->foreign_key) {
            return [
                $this->translation,
                $this->foreign_key
            ];
        }
        $tableName = $this->getModel()->getTable();
        $tableName = substr($tableName, 0, -1);
        return [
            $tableName . '_translations',
            $tableName . '_id'
        ];
    }

    public function deleteRelationship($table)
    {
        $tableName = $this->getModel()->getTable();
        [$tabelNameTranslation,$foreign_key] = $this->getTableNameTranslation();
        foreach ($table->$tableName()->get() as $item) {
            $item->$tabelNameTranslation()->delete();
        }
        $table->$tableName()->delete();
    }

    public function getRequested($id)
    {
        $row = $this->getModel()->findOrFail($id);
        if (is_null($row)) {
            throw new IncException\GeneralException('AWE007');
        }
        return $row;
    }

    public function createManyRelationship($row, $foreignTable, $data)
    {
        if (!$data) {
            return;
        }
        $table = $this->getModel()->getTable();
        $key = substr($table, 0, -1)."_id";
        array_walk($data, function ($v, $k) use (&$data, $key, $row) {
            $data[$k][$key] = $row->id;
        });
        $row->$foreignTable()->createMany($data);
    }

    public function updateManyRelationship($row, $foreignTable, $data = [])
    {
        if (is_null($data)) {
            return;
        }
        //delete
        $this->deleteManyRelationship($row, $foreignTable);
        //create
        $this->createManyRelationship($row, $foreignTable, $data);
    }

    public function deleteManyRelationship($row, $foreignTable)
    {
        return $row->$foreignTable()->delete();
    }

    public function deleteWhereIn($array)
    {
        [$tabelName,$foreign_key] = $this->getTableNameTranslation();
        DB::table($tabelName)->whereIn($foreign_key, $array)->delete();
        $this->getModel()->whereIn('id', $array)->delete();
    }

    public function forceDelete($id, $trigger = false)
    {
        $query = $this->createQueryBuilder();
        $query->where($this->getPrimaryKey($query), $id);
        if ($trigger) {
            $query->first()->delete();
        } else {
            $query->forceDelete();
        }
    }
}
