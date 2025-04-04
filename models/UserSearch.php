<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User {

    public $congregacion;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'genero', 'status', 'created_at', 'updated_at'], 'integer'],
            [[
                'username',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'nombre',
                'apellido',
                'apellido_casada',
                'telefono',
                'ultima_sesion',
                'email',
                'verification_token',
                'congregacion'
            ], 'safe'],
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
        $query = User::find()->joinWith(["congregacion"]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC], // Ordenar por defecto por 'id'
                'attributes' => [
                    'id',
                    'username',
                    'user.nombre',
                    'apellido',
                    'apellido_casada',
                    'congregacion' => [
                        'asc' => ['congregacion.nombre' => SORT_ASC],
                        'desc' => ['congregacion.nombre' => SORT_DESC],
                    ],
                    'email',
                    'ultima_sesion',
                    'status',
                ],
            ],
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
            'genero' => $this->genero,
            'ultima_sesion' => $this->ultima_sesion,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'user.nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'apellido_casada', $this->apellido_casada])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'verification_token', $this->verification_token])
            ->andFilterWhere(['like', 'congregacion.nombre', $this->congregacion]);

        return $dataProvider;
    }
}
