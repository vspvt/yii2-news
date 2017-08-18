<?php

namespace app\rbac;

use app\components\UserPermissions;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class OwnerRule
 * @package app\rbac
 */
class OwnerRule extends Rule
{
    public $name = UserPermissions::RULE_IS_OWNER;

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['post']) && is_object($params['post']) ? $params['post']->user_id === $user : false;
    }

}
