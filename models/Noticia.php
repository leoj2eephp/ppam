<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "noticia".
 *
 * @property int $id
 * @property string $titulo
 * @property string $contenido
 * @property string $fecha
 * @property int $estado
 * @property int $user_id
 *
 * @property User $user
 */
class Noticia extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'noticia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['titulo', 'contenido', 'fecha', 'user_id'], 'required'],
            [['contenido'], 'string'],
            [['fecha'], 'safe'],
            [['estado', 'user_id'], 'integer'],
            [['titulo'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'contenido' => 'Contenido',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
