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
 * @property User $user1
 * @property User $user2
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
            'confirmado1' => 'Confirmado 1',
            'confirmado2' => 'Confirmado 2',
            'no_realizado' => 'No Realizado',
            'user_id1' => 'Voluntario 1',
            'user_id2' => 'Voluntario 2',
            'turno_id' => 'Turno',
            'punto_id' => 'Punto',
        ];
    }

    public function fields() {
        $fields = parent::fields();
        // Incluye los datos relacionados de los usuarios en la respuesta JSON
        $fields['user1'] = function ($model) {
            return [
                'id' => $model->user1->id,
                'username' => $model->user1->username,
                'nombre' => $model->user1->nombre,
                'apellido' => $model->user1->apellido,
                'apellido_casada' => $model->user1->apellido_casada,
                'telefono' => $model->user1->telefono,
                'genero' => $model->user1->genero,
                'email' => $model->user1->email,
            ];
        };
        $fields['user2'] = function ($model) {
            return [
                'id' => $model->user2->id,
                'username' => $model->user2->username,
                'nombre' => $model->user2->nombre,
                'apellido' => $model->user2->apellido,
                'apellido_casada' => $model->user2->apellido_casada,
                'telefono' => $model->user2->telefono,
                'genero' => $model->user2->genero,
                'email' => $model->user2->email,
            ];
        };
        $fields["punto"] = function ($model) {
            return [
                "id" => $model->id,
                "nombre" => $model->punto->nombre,
                "latitud" => $model->punto->latitud,
                "longitud" => $model->punto->longitud,
                "color" => $model->punto->color,
            ];
        };
        $fields["turno"] = function ($model) {
            return [
                "id" => $model->id,
                "nombre" => $model->turno->nombre,
                "desde" => $model->turno->desde,
                "hasta" => $model->turno->hasta,
            ];
        };
        
        return $fields;
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
     * Gets query for [[user1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser1() {
        return $this->hasOne(User::class, ['id' => 'user_id1']);
    }

    /**
     * Gets query for [[user2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser2() {
        return $this->hasOne(User::class, ['id' => 'user_id2']);
    }
}
