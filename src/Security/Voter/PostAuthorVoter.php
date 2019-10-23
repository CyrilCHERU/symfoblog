<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PostAuthorVoter extends Voter
{
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute === 'POST_EDIT'
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // 1. Si Reviewer, droit de passer et de modifier son article
        if ($this->security->isGranted("ROLE_REVIEWER")) {
            return true;
        }
        // // 2. Si Author et que l'article est le sien il a le droit de modifier
        // if ($this->security->isGranted("ROLE_AUTHOR") && 31 === $user->getId()) {
        //     return true;
        // }
        // Sinon il degage


        // ... (check conditions and return true to grant permission) ...
        // switch ($attribute) {
        //     case 'POST_EDIT':
        //         // logic to determine if the user can EDIT
        //         // return true or false

        //         break;
        //     case 'POST_VIEW':
        //         // logic to determine if the user can VIEW
        //         // return true or false
        //         break;
        // }

        return false;
    }
}
