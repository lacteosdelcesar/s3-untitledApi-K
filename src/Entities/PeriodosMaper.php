<?php namespace App\Entities;

use Spot\Mapper;

class PeriodosMaper extends Mapper
{
    public function getActual()
    {
        return $this->query('SELECT p.* FROM periodos p JOIN periodo_actual pa ON p.id=pa.periodo_id')[0];
    }
}