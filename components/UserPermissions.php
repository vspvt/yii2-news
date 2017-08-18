<?php

namespace app\components;

class UserPermissions
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_USER = 'user';

    const RULE_IS_OWNER = 'isOwner';

    const PERMISSION_UPDATE_POST = 'updatePost';
    const PERMISSION_UPDATE_OWN_POST = 'updateOwnPost';
}
