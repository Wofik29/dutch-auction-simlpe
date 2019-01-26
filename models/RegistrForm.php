<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegistrForm is the model behind the registr form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegistrForm extends Model
{
    public $username;
    public $password;
    public $role;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'role'], 'required'],
            ['role', 'validateRole'],
            ['username', 'unique', 'targetClass' => User::class,  'message' => 'Этот логин уже занят'],
        ];
    }

    /**
     * Validates the role.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateRole($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $roles = array_keys(User::getRoles());
            if (!in_array($this->$attribute, $roles)) {
                $this->addError($attribute, Yii::t('app', 'Role not exist'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function run()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->password = $this->password;

            if ($user->save()) {
                $role = Yii::$app->authManager->getRole($this->role);
                Yii::$app->authManager->assign($role,$user->id);
                return true;
            }

            $this->addErrors($user->getErrors());
        }

        return false;
    }

}
