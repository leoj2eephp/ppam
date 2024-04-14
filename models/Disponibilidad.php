<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "disponibilidad".
 *
 * @property int $id
 * @property int $turno_id
 * @property int $user_id
 * @property int $dia
 * @property int $estado
 *
 * @property Turno $turno
 * @property User $user
 */
class Disponibilidad extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'disponibilidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['turno_id', 'user_id', 'dia'], 'required'],
            [['turno_id', 'user_id', 'dia', "estado"], 'integer'],
            [['turno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Turno::class, 'targetAttribute' => ['turno_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'turno_id' => 'Turno ID',
            'user_id' => 'User ID',
            'dia' => 'Dia',
        ];
    }

    public function fields() {
        $fields = parent::fields();
        // Incluye los datos relacionados de los usuarios en la respuesta JSON
        $fields['turno'] = function ($model) {
            return [
                'id' => $model->turno->id,
                'nombre' => $model->turno->nombre,
                'desde' => $model->turno->desde,
                'hasta' => $model->turno->hasta,
                'estado' => $model->turno->estado,
                'orden' => $model->turno->orden,
            ];
        };
        $fields['user'] = function ($model) {
            return [
                "id" => $model->user->id,
                'username' => $model->user->username,
                'email' => $model->user->email,
                'nombre' => $model->user->nombre,
                'apellido' => $model->user->apellido,
                'apellido_casada' => $model->user->apellido_casada,
                'genero' => $model->user->genero,
                'telefono' => $model->user->telefono,
            ];
        };

        return $fields;
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
