<?php


namespace App\Security;

use App\Entity\User;

final class Role
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_EDITEUR = 'ROLE_EDITEUR';
    const ROLE_GESTIONNAIRE = 'ROLE_GESTIONNAIRE';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Défini si l'utilisateur est administrateur
     *
     * @param User $user
     * @return bool
     */
    public static function isAdmin(?User $user)
    {
        if (null === $user) {
            return false;
        }

        if (in_array(self::ROLE_ADMIN, $user->getRoles())) {
            return true;
        }
        return false;
    }

    /**
     * Défini si l'utilisateur est gestionnaire
     *
     * @param User $user
     * @return bool
     */
    public static function isGestionnaire(?User $user)
    {
        if (null === $user) {
            return false;
        }
        if (in_array(self::ROLE_GESTIONNAIRE, $user->getRoles()) OR self::isAdmin($user)) {
            return true;
        }
        return false;
    }

    /**
     * Défini si l'utilisateur a l'habilitation éditeur
     *
     * @param User $user
     * @return bool
     */
    public static function isEditeur(?User $user)
    {
        if (null === $user) {
            return false;
        }
        if (in_array(self::ROLE_EDITEUR, $user->getRoles()) OR self::isAdmin($user) OR self::isGestionnaire($user)) {
            return true;
        }
        return false;
    }

    /**
     * Défini si l'utilisateur a l'habilitation utilisateur
     *
     * @param User $user
     * @return bool
     */
    public static function isUser(?User $user)
    {
        if (null === $user) {
            return false;
        }
        if (in_array(self::ROLE_USER, $user->getRoles()) OR self::isAdmin($user) OR self::isGestionnaire($user) OR self::isEditeur($user)) {
            return true;
        }
        return false;
    }
}
