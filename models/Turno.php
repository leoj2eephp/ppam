<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "turno".
 *
 * @property int $id
 * @property string $nombre
 * @property string $desde
 * @property string $hasta
 * @property int $estado
 * @property int|null $orden
 *
 * @property Asignacion[] $asignacions
 * @property Punto[] $puntos
 * @property TurnoPunto[] $turnoPuntos
 */
class Turno extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'turno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nombre', 'desde', 'hasta', 'estado'], 'required'],
            [['desde', 'hasta'], 'safe'],
            [['estado'], 'integer'],
            [['nombre'], 'string', 'max' => 45],
            // Anula la regla de validación para el escenario 'guardar'
            ['orden', 'integer', 'except' => 'save'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'estado' => 'Estado',
            'orden' => 'Orden',
        ];
    }

    /**
     * Gets query for [[Asignacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacions() {
        return $this->hasMany(Asignacion::class, ['turno_id' => 'id']);
    }

    /**
     * Gets query for [[Puntos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuntos() {
        return $this->hasMany(Punto::class, ['id' => 'punto_id'])->viaTable('turno_punto', ['turno_id' => 'id']);
    }

    /**
     * Gets query for [[TurnoPuntos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurnoPuntos() {
        return $this->hasMany(TurnoPunto::class, ['turno_id' => 'id']);
    }

    public function getHorario() {
        return $this->nombre . " (" . substr($this->desde, 0, 5) . "-" . substr($this->hasta, 0, 5) . ")";
    }
}
