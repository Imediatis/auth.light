<?php
namespace Digitalis\Core\Models\DbAdapters;

use Digitalis\Core\Models\Data;
use Digitalis\Core\Models\Lexique;
use Digitalis\Core\Models\Entities\User;
use Digitalis\Core\Handlers\ErrorHandler;

/**
 * UserDbAdapter Gestionnaire des utilisateurs avec la base de donnée
 *
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM<sylvin@imediatis.net> (Back-end Developper)
 */
class UserDbAdapter
{
    /**
     * enregistre les informations de dernière connexion de l'utilisateur
     *
     * @param string $login
     * @return boolean
     */
    public static function setLastLogin($login)
    {
        Data::sendData('users/' . $login . '/lastlogin', null, "PUT");
        return true;
    }

    /**
     * Permet d'enregistrer les informations de dernière déconnexion de l'utilisateur
     *
     * @param string $login
     * @return boolean
     */
    public static function setLastLogout($login)
    {
        $out = Data::sendData('users/' . $login . '/lastlogout', null, "PUT");
        return true;
    }

    /**
     * Permet de récupérer un utilisateur à partir de son login
     *
     * @param string $login
     * @return User
     */
    public static function getByLogin($login)
    {
        try {
            $repos = Data::getData('users/' . $login);
            if (!isset($repos['data'])) {
                if (isset($repos['message']) && strlen($repos['message']) > 0)
                    Data::setErrorMessage($repos['message']);
                return null;
            }
            return User::buildInstance($repos['data']);
        } catch (\Exception $exc) {
            Data::setErrorMessage(Lexique::GetString(CUR_LANG, an_error_occured));
            ErrorHandler::writeLog($exc);
        }
        return null;
    }

    /**
     * Permet de marquer la dernière action de l'utilisateur
     *
     * @param string $login
     * @return boolean
     */
    public static function setLastAction($login)
    {
        Data::sendData('users/' . $login . '/lastaction', null, "PUT");
        return false;
    }

    /**
     * Permet de générer le token de l'utilisateur
     *
     * @param string $login
     * @return User
     */
    public static function genToken($login)
    {
        $reponse = Data::sendData('users/' . $login . '/token', null, "PUT");
        if(isset($reponse['message'])&&$reponse['message'])
            Data::setErrorMessage($reponse['message']);

        if (isset($reponse['data']) && $reponse['data'])
            return User::buildInstance($reponse['data']);
        return null;
    }
    
    public static function lockUserAccount( $login)
    {
        Data::sendData('users/' . $login . '/lockaccount', null, "PUT");
        return true;
    }
}
