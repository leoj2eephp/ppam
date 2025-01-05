<?php

namespace app\models;

use app\components\Helper;
use Exception;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $nombre
 * @property string $apellido
 * @property string|null $apellido_casada
 * @property int $genero
 * @property string $telefono
 * @property string|null $ultima_sesion
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $congregacion_id
 * @property string|null $rol
 * @property string|null $verification_token
 * @property string|null $access_token
 * @property string|null $device_token
 * @property string|null $condicion_especial
 *
 * @property Asignacion[] $asignacions
 * @property Asignacion[] $asignacions0
 * @property Congregacion $congregacion
 */
class User extends ActiveRecord implements IdentityInterface {
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    public $rol;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['username', 'auth_key', 'password_hash', 'nombre', 'apellido', 'genero', 'telefono', 'email', 'created_at', 'updated_at',], 'required'],
            [['genero', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ultima_sesion', 'condicion_especial', 'rol', 'congregacion_id'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'access_token', 'device_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['nombre', 'apellido', 'apellido_casada'], 'string', 'max' => 45],
            [['telefono'], 'string', 'max' => 15],
            [['username'], 'unique'],
            // [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['username', 'auth_key', 'created_at', 'updated_at',], 'safe', 'except' => 'create'],
            [['congregacion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Congregacion::class, 'targetAttribute' => ['congregacion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Nombre Usuario',
            'auth_key' => 'Contraseña',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'apellido_casada' => 'Apellido Casada',
            'genero' => 'Género',
            'telefono' => 'Teléfono',
            'ultima_sesion' => 'Ultima Sesion',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'rol' => 'Rol',
            'congregacion_id' => 'Congregación',
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['limitedInfo'] = ['username', 'email', 'nombre', 'apellido', 'apellido_casada', 'genero', 'telefono'];
        return $scenarios;
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['rol'] = function ($model) {
            return $model->rol;
        };
        return $fields;
    }

    /**
     * Gets query for [[Asignacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacions() {
        return $this->hasMany(Asignacion::class, ['user_id1' => 'id']);
    }

    /**
     * Gets query for [[Asignacions0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacions0() {
        return $this->hasMany(Asignacion::class, ['user_id2' => 'id']);
    }

    /**
     * Gets query for [[Congregacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCongregacion() {
        return $this->hasOne(Congregacion::class, ['id' => 'congregacion_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public function limitInfo() {
        unset($this->auth_key);
        unset($this->password_hash);
        unset($this->password_reset_token);
        unset($this->ultima_sesion);
        unset($this->status);
        unset($this->created_at);
        unset($this->updated_at);
        unset($this->verification_token);
        unset($this->access_token);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken() {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public function createUser() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $primerCaracter = strtolower(Helper::remove_accents(substr($this->nombre, 0, 1)));
            $usernameBase = $primerCaracter . strtolower(Helper::remove_accents($this->apellido));
            $existingUser = User::find()->where(['username' => $usernameBase])->one();
            $counter = 2;
            // Mientras exista un usuario con el mismo username, agregar un número al final
            while ($existingUser !== null) {
                $usernameBase = $usernameBase . $counter;
                $existingUser = User::find()->where(['username' => $usernameBase])->one();
                $counter++;
            }

            $this->username = $usernameBase;
            $this->setPassword(strtolower(Helper::remove_accents($this->apellido)) . "1234");
            $this->generateAuthKey();
            $this->generateEmailVerificationToken();
            $this->status = User::STATUS_ACTIVE;
            $this->created_at = time();
            $this->updated_at = time();
            if ($this->save()) {
                if ($this->save()) {
                    $auth = Yii::$app->authManager;
                    $auth->assign($auth->getRole($this->rol), $this->id);
                    // Crear todas las disponibilidades
                    $this->asignarDisponibilidad();
                    // Enviar correo y hacer los pasos respectivos para finalizar la creación del usuario
                    // Mailer::send($this->email, "Usuario creado en PPAM", "/mail/newUserCreated", ["model" => $this]);
                    \Yii::$app->session->setFlash("success", "Usuario creado exitosamente!");
                    \Yii::$app->session->setFlash("success", "Se envió correo de notificación");
                    $transaction->commit();
                } else {
                    throw new Exception(join(", ", $this->getFirstErrors()));
                }
            } else {
                throw new Exception(join(", ", $this->getFirstErrors()));
            }
        } catch (Exception $ex) {
            \Yii::$app->session->setFlash("danger", "Hubo un problema: " . $ex->getMessage());
            $transaction->rollBack();
        }
    }

    /* private function asignarRolesPermisos() {
        $auth = \Yii::$app->authManager;
        $authorRole = $auth->getRole("usuario");
        $auth->assign($authorRole, $this->id);
        $funciones = \app\models\AuthItemChild::find()->where(['parent' => "usuario"])->select(['child'])->all();
        foreach ($funciones as $funcion) {
            $permiso = new \app\models\AuthAssignment();
            $permiso->user_id = $this->id;
            $permiso->item_name = $funcion;
            $permiso->save();
        }
    } */

    private function asignarDisponibilidad() {
        foreach (Dias::getAll() as $dia) {
            $turnos = Turno::find()->orderBy("orden")->all();
            foreach ($turnos as $turno) {
                $disponibilidad = new Disponibilidad();
                $disponibilidad->user_id = $this->id;
                $disponibilidad->turno_id = $turno->id;
                $disponibilidad->estado = 0;
                $disponibilidad->dia = Dias::getIntDay($dia);
                if (!$disponibilidad->save()) throw new Exception(join(",", $disponibilidad->getFirstErrors()));
            }
        }
    }

    public function getNombreCompleto() {
        if ($this->genero == 1) {
            return $this->nombre . " " . $this->apellido;
        } else if ($this->genero == 2) {
            return $this->apellido_casada !== "" ? $this->nombre . " " . $this->apellido . " de " . $this->apellido_casada :
                $this->nombre . " " . $this->apellido;
        }
    }

    public static function getUserRolesById($userId) {
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($userId);   
        // Si quieres solo los nombres de los roles
        $roleNames = array_keys($roles);
        return $roleNames;
    }

    public function updateRol() {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($this->id);
        $role = $auth->getRole($this->rol);
        $auth->assign($role, $this->id);
    }

    public static function updateLastSession($id) {
        $user = User::find(["id" => $id])->one();
        $user->ultima_sesion = date('Y-m-d');
        return $user->save();
    }
}
