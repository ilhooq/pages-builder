<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\site\models;

use piko\IdentityInterface;
use piko\Model;
use piko\Piko;

/**
 * This is the model class for table "user".
 *
 */
class User extends Model implements IdentityInterface
{
    public $id;
    public $username;
    public $password;

    /**
     * @param string $username
     * @return \app\modules\site\models\User|NULL
     */
    public static function findByUsername($username)
    {
        $users = Piko::$app->config['users'];

        foreach ($users as $user) {
            if ($username == $user['username']) {
                return new static($user);
            }
        }

        return null;
    }

    public function validatePassword($password)
    {
        return $this->password == $password;
    }

    /**
     * @param int $id
     * @return \app\modules\site\models\User|NULL
     */
    public static function findIdentity($id)
    {
        $users = Piko::$app->config['users'];

        foreach ($users as $user) {
            if ($id == $user['id']) {
                return new static($user);
            }
        }

        return null;
    }

    public function getId()
    {
        return $this->id;
    }

}