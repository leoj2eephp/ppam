<?php
namespace app\models;

class Dias {
    public const Lunes = 'Lunes';
    public const Martes = 'Martes';
    public const Miercoles = 'Miércoles';
    public const Jueves = 'Jueves';
    public const Viernes = 'Viernes';
    public const Sabado = 'Sábado';
    public const Domingo = 'Domingo';

    public static function getAll() {
        return [1=>Dias::Lunes, 2=>Dias::Martes, 3=>Dias::Miercoles, 4=>Dias::Jueves, 5=>Dias::Viernes, 6=>Dias::Sabado, 7=>Dias::Domingo];
    }

    public static function getIntDay($dia) {
        return array_search($dia, self::getAll());
    }
}
