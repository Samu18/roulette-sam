<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AllVoter extends Voter
{
    /**
     * AllVoter constructor.
     */

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof \App\Entity\User) {
            return false;
        }

        /*Consulta para obtener un registro desde los roles o grants para ver si el usuario tiene acceso o no*/
        try {

            $permissions = strpos($user->getPermissions(), "|" . $attribute . "|");
            $permissionsPublic = "|dashboard|";
            $permissionsPublic = strpos($permissionsPublic, "|" . $attribute . "|");

            if ($permissionsPublic === false && $permissions === false  && $user->getPermissions() != "x") {
                return false;
            } else {
                return true;
            }

        } catch (\Exception $ex) {
            dump('An error occurred checking grants of the user');
            die();
        }

    }
}