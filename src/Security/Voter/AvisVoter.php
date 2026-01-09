<?php

namespace App\Security\Voter;

use App\Entity\Avis;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

class AvisVoter extends Voter
{
    const EDIT = 'EDIT';
    const MODERATE = 'MODERATE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::MODERATE]) && $subject instanceof Avis;
    }

    /**
     * @param string $attribute
     * @param Avis $subject
     * @param TokenInterface $token
     * @param Vote|null $vote
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) return false;

        /** @var Avis $avis */
        $avis = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $user === $avis->getUtilisateur();

            case self::MODERATE:
                $roleAdmin = in_array('ROLE_ADMIN', $user->getRoles());
                $roleResp = in_array('ROLE_RESPONSABLE', $user->getRoles())
                    && $avis->getEvenement()->getResponsable() === $user;

                return $roleAdmin || $roleResp;
        }

        return false;
    }
}
