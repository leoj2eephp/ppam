<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ciudad".
 *
 * @property int $id
 * @property string $nombre
 *
 * @property Congregacion[] $congregacions
 */
class Ciudad extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'ciudad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[Congregacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCongregacions() {
        return $this->hasMany(Congregacion::class, ['ciudad_id' => 'id']);
    }
}
