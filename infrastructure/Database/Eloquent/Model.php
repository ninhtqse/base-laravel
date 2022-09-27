<?php

namespace Infrastructure\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;
use DateTimeInterface;

abstract class Model extends BaseModel
{
    protected $keyType = 'string';

    protected $guarded = [];

    public $incrementing = false;

    protected function serializeDate(DateTimeInterface $date)
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}
