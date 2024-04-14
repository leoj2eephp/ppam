<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "turno_punto".
 *
 * @property int $turno_id
 * @property int $punto_id
 * @property int $dia
 *
 * @property Punto $punto
 * @property Turno $turno
 */
class TurnoPunto extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'turno_punto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['turno_id', 'punto_id', 'dia'], 'required'],
            [['turno_id', 'punto_id', 'dia'], 'integer'],
            /* [['turno_id'], 'unique'],
            [['punto_id'], 'unique'],
            [['dia'], 'unique'], */
            [['turno_id', 'punto_id', 'dia'], 'unique', 'targetAttribute' => ['turno_id', 'punto_id', 'dia']],
            [['punto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Punto::class, 'targetAttribute' => ['punto_id' => 'id']],
            [['turno_id'], 'exist', 'skipOnError' => true, 'targetClass' => Turno::class, 'targetAttribute' => ['turno_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'turno_id' => 'Turno',
            'punto_id' => 'Punto',
            'dia' => 'Día',
        ];
    }

    public function fields() {
        $fields = parent::fields();
        $fields['punto'] = function () {
            return $this->punto;
        };
        $fields['turno'] = function () {
            return $this->turno;
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
        return $this->hasOne(Turno::class, ['id' => 'turno_id'])->orderBy("orden ASC");
    }
}
