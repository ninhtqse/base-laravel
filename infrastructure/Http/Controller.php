<?php

namespace Infrastructure\Http;

use Illuminate\Support\Facades\Validator;
use Ninhtqse\Bruno\LaravelController;
use Infrastructure\Exceptions as IncException;

abstract class Controller extends LaravelController
{
    public function validateIds($ids, $attr = 'id')
    {
        foreach ($ids as $value) {
            $validator = Validator::make(["id" => $value], [
                'id' => 'uuid'
            ], [
                'id.uuid' => $attr . ' phải đúng định dạng uuid'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                throw new IncException\GeneralException("E001", 'Dữ liệu đầu vào không hợp lệ: ' . $error);
            }
        }
    }
    public function validateId($id, $attr = 'id')
    {
        $validator = Validator::make(["id" => $id], [
            'id' => 'uuid'
        ], [
            'id.uuid' => $attr . ' phải đúng định dạng uuid'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            throw new IncException\GeneralException("AWE004", 'Dữ liệu đầu vào không hợp lệ: ' . $error);
        }
    }
}
