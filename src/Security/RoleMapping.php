<?php

namespace App\Security;

class RoleMapping
{
    const ARTICLE = [
        'Default' => 'ROLE_ARTICLE_CATEGORY_DEFAULT',
        'PHP' => 'ROLE_ARTICLE_CATEGORY_PHP',
        'Golang' => 'ROLE_ARTICLE_CATEGORY_GOLANG',
        'Ops' => 'ROLE_ARTICLE_CATEGORY_OPS',
    ];
}
