<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


class UserVoter extends Voter
{
    private $security;

    // $container = new Container
    // $security = new Security($container)
    // Auto wiring
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Lorsqu'on appelle la méthode denyAccessUnlessGranted
     * Symfony va interroger tous les Voters existants (src/Security/Voter)
     * en executant leur méthode supports, qui devra retourner 
     * un Booléen (VRAI/FAUX) pour signaler si OUI ou NON ils prennent
     * en charge un certain type d'autorisation.
     *
     * @param string $attribute
     * @param [type] $subject
     * @return boolean
     */
    protected function supports(string $attribute, $subject): bool
    {
        // $attribute ==> USER_EDIT
        // $subject ==> $user
        // dump($attribute, $subject);

        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // Cette ligne retournera VRAI si les deux conditions
        // sont respectées : 
        // 
        return in_array($attribute, ['USER_EDIT', 'USER_VIEW'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Si on a passé le test de la méthode supports,
        // on attérit dans la méthode voteOnAttribute

        // dd('On est bien dans la méthode voteOnAttribute');

        // $user est l'utilisateur actuellement connecté
        $currentUser = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        // On passe la barrière dans 2 cas précis :
        // 1) On doit être le propriété du compte ou alors
        // 2) On doit être SuperAdmin
        // if ($attribute === 'USER_EDIT') {
        //     ///
        // } elseif ($attribute === 'USER_VIEW') {
        //     // 
        // }

        $userRoles = $subject->getRoles();

        switch ($attribute) {
            case 'USER_EDIT':
                // On vérifie si on est propriétaire du compte
                // $currentUser : l'utilisateur connecté
                // $subject : l'utilisateur dont on veut éditer le compte
                // return $currentUser == $subject;
                // $this->security->isGranted('ROLE_SUPER_ADMIN') retourne :
                // - VRAI : si la personne connectée a un role SUPERADMIN
                // - FAUX : dans le cas contraire
                if ($currentUser === $subject || $this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }

                // Si l'utilisateur à éditer est un simple mortel (ROLE_USER)
                // Admin devrait pouvoir l'éditer
                // Proposition de Daniel
                // if (in_array('ROLE_ADMIN', $user->getRoles()) and in_array('ROLE_USER', $subject->getRoles())) {
                //     return true;
                // }
                // Proposition de Frederic 
                // if ($userRoles[0] == 'ROLE_USER' && $this->security->isGranted('ROLE_ADMIN')) {
                //     return true;
                // }
                if (count($userRoles) === 1 && $userRoles[0] == 'ROLE_USER') {
                    return true;
                }

                break;
            case 'USER_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
