<?php

namespace app\commands;

use app\components\UserPermissions;
use app\models\User;
use app\rbac\OwnerRule;
use Yii;
use yii\base\InvalidParamException;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @return int
     */
    public function actionInit()
    {
        if (!$this->confirm('Are you sure? It will re-create permissions tree.')) {
            return self::EXIT_CODE_NORMAL;
        }

        $auth = Yii::$app->authManager;
        $auth->removeAll();

        # Roles
        $adminRole = $auth->createRole(UserPermissions::ROLE_ADMIN);
        $auth->add($adminRole);
        $moderatorRole = $auth->createRole(UserPermissions::ROLE_MODERATOR);
        $auth->add($moderatorRole);
        $userRole = $auth->createRole(UserPermissions::ROLE_USER);
        $auth->add($userRole);

        # Permissions & rules
        $updatePostPermission = $auth->createPermission(UserPermissions::PERMISSION_UPDATE_POST);
        $updatePostPermission->description = 'Update posts';
        $auth->add($updatePostPermission);

        $ownerRule = new OwnerRule;
        $auth->add($ownerRule);

        $updateOwnPostPermisssion = $auth->createPermission(UserPermissions::PERMISSION_UPDATE_OWN_POST);
        $updatePostPermission->description = 'Update own posts';
        $updateOwnPostPermisssion->ruleName = $ownerRule->name;
        $auth->add($updateOwnPostPermisssion);

        $auth->addChild($updateOwnPostPermisssion, $updatePostPermission);
        $auth->addChild($moderatorRole, $updateOwnPostPermisssion);
        $auth->addChild($adminRole, $updatePostPermission);

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * @param string $role
     * @param string $username
     */
    public function actionAssign($role, $username)
    {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            throw new InvalidParamException("There is no user \"$username\".");
        }
        $auth = Yii::$app->authManager;
        $roleObject = $auth->getRole($role);
        if (!$roleObject) {
            throw new InvalidParamException("There is no role \"$role\".");
        }
        $auth->assign($roleObject, $user->id);
    }

}
