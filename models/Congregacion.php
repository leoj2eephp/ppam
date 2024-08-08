<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "congregacion".
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $numero
 * @property string|null $circuito
 * @property int $ciudad_id
 *
 * @property Ciudad $ciudad
 */
class Congregacion extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'congregacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nombre', 'ciudad_id'], 'required'],
            [['numero', 'ciudad_id'], 'integer'],
            [['nombre'], 'string', 'max' => 45],
            [['circuito'], 'string', 'max' => 10],
            [['ciudad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ciudad::class, 'targetAttribute' => ['ciudad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'numero' => 'Número',
            'circuito' => 'Circuito',
            'ciudad_id' => 'Ciudad',
        ];
    }

    /**
     * Gets query for [[Ciudad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCiudad() {
        return $this->hasOne(Ciudad::class, ['id' => 'ciudad_id']);
    }
}
