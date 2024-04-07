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
        return [Dias::Lunes, Dias::Martes, Dias::Miercoles, Dias::Jueves, Dias::Viernes, Dias::Sabado, Dias::Domingo];
    }
}
