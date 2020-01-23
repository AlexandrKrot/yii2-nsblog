<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace koperdog\yii2nsblog\models\dto;

/**
 * Description of BaseDto
 *
 * @author Koperdog <koperdog.dev@gmail.com>
 * @version 1.0
 */
abstract class BaseDto {
    public function import(array $data)
    {
        foreach($data as $key => $value){
            $this->$key = $value;
        }
    }
}
