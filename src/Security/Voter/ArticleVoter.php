<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Security\RoleMapping;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return \in_array($attribute, ['ARTICLE_EDIT'], true)
            && $subject instanceof Article;
    }

    protected function voteOnAttribute($attribute, $article, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ('ARTICLE_EDIT' === $attribute) {
            // ADMIN can do anything
            if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return true;
            }

            $roleNeed = RoleMapping::ARTICLE[$article->getCategory()] ?? false;
            if (\in_array($roleNeed, $user->getRoles(), true)) {
                return true;
            }

            return false;
        }

        return false;
    }
}
