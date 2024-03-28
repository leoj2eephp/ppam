<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Asignacion;

/**
 * AsignacionSearch represents the model behind the search form of `common\models\Asignacion`.
 */
class AsignacionSearch extends Asignacion {
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'confirmado1', 'confirmado2', 'no_realizado', 'user_id1', 'user_id2', 'turno_id', 'punto_id'], 'integer'],
            [['fecha'], 'safe'],
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
        $query = Asignacion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fecha' => $this->fecha,
            'confirmado1' => $this->confirmado1,
            'confirmado2' => $this->confirmado2,
            'no_realizado' => $this->no_realizado,
            'user_id1' => $this->user_id1,
            'user_id2' => $this->user_id2,
            'turno_id' => $this->turno_id,
            'punto_id' => $this->punto_id,
        ]);

        return $dataProvider;
    }
}
