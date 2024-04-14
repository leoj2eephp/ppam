<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "punto".
 *
 * @property int $id
 * @property string $nombre
 * @property string $latitud
 * @property string $longitud
 * @property string $color
 *
 * @property Asignacion[] $asignacions
 * @property TurnoPunto[] $turnoPuntos
 * @property Turno[] $turnos
 */
class Punto extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'punto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nombre', 'color'], 'required'],
            [['nombre', 'latitud', 'longitud'], 'string', 'max' => 45],
            [['color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'color' => 'Color',
        ];
    }

    public function fields() {
        $fields = parent::fields();
        $fields['turnos'] = function ($model) {
            $turnos = [];
            foreach ($model->turnoPuntos as $turnoPunto) {
                $turno = $turnoPunto->turno;
                if ($turno !== null) {
                    $turnos[] = [
                        'id' => $turno->id,
                        'nombre' => $turno->nombre,
                        'desde' => $turno->desde,
                        'hasta' => $turno->hasta,
                        'estado' => $turno->estado,
                        'orden' => $turno->orden,
                    ];
                }
            }
            return $turnos;
        };

        return $fields;
    }

    /**
     * Gets query for [[Asignacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacions() {
        return $this->hasMany(Asignacion::class, ['punto_id' => 'id']);
    }

    /**
     * Gets query for [[TurnoPuntos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurnoPuntos() {
        return $this->hasMany(TurnoPunto::class, ['punto_id' => 'id']);
    }

    /**
     * Gets query for [[Turnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurnos() {
        return $this->hasMany(Turno::class, ['id' => 'turno_id'])->viaTable('turno_punto', ['punto_id' => 'id']);
    }
}
