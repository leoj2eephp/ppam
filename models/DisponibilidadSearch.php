<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Disponibilidad;

/**
 * DisponibilidadSearch represents the model behind the search form of `app\models\Disponibilidad`.
 */
class DisponibilidadSearch extends Disponibilidad {

    public $nombreCompleto;
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'turno_id', 'user_id', 'dia'], 'integer'],
            [["nombreCompleto"], "safe"]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Disponibilidad::find();
        $query->joinWith(["user"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'turno_id' => $this->turno_id,
            'user_id' => $this->user_id,
            'dia' => $this->dia,
        ]);

        // Condición personalizada basada en el modelo relacionado
        $query->andFilterWhere(['like', 'user.nombre', $this->nombreCompleto]);
        $query->orFilterWhere(['like', 'user.apellido', $this->nombreCompleto]);
        return $dataProvider;
    }
}
