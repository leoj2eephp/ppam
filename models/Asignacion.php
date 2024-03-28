<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asignacion".
 *
 * @property int $id
 * @property string $fecha
 * @property int|null $confirmado1
 * @property int|null $confirmado2
 * @property int|null $no_realizado
 * @property int $user_id1
 * @property int $user_id2
 * @property int $turno_id
 * @property int $punto_id
 *
 * @property Punto $punto
 * @property Turno $turno
 * @property User $userId1
 * @property User $userId2
 */
class Asignacion extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asignacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fecha', 'user_id1', 'user_id2', 'turno_id', 'punto_id'], 'required'],
            [['fecha'], 'safe'],
            [['confirmado1', 'confirmado2', 'no_realizado', 'user_id1', 'user_id2', 'turno_id', 'punto_id'], 'integer'],
            [['punto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Punto::class, 'targetAttribute' => ['punto_id' => 'id']],
            [['turno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Turno::class, 'targetAttribute' => ['turno_id' => 'id']],
            [['user_id1'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id1' => 'id']],
            [['user_id2'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id2' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'confirmado1' => 'Confirmado1',
            'confirmado2' => 'Confirmado2',
            'no_realizado' => 'No Realizado',
            'user_id1' => 'User Id1',
            'user_id2' => 'User Id2',
            'turno_id' => 'Turno ID',
            'punto_id' => 'Punto ID',
        ];
    }

    /**
     * Gets query for [[Punto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPunto() {
        return $this->hasOne(Punto::class, ['id' => 'punto_id']);
    }

    /**
     * Gets query for [[Turno]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurno() {
        return $this->hasOne(Turno::class, ['id' => 'turno_id']);
    }

    /**
     * Gets query for [[UserId1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserId1() {
        return $this->hasOne(User::class, ['id' => 'user_id1']);
    }

    /**
     * Gets query for [[UserId2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserId2() {
        return $this->hasOne(User::class, ['id' => 'user_id2']);
    }
}
