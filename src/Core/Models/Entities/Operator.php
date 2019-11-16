<?php

namespace Digitalis\Core\Models\Entities;

use DateTime;
use DateTimeZone;
use Digitalis\Core\Models\Entities\Caisse;
use Digitalis\Core\Models\Security\LoggedUser;

/**
 * Operator Description of Operator here
 *
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM<sylvin@imediatis.net> (Back-end Developper)
 */
class Operator
{
    /**
     * Identifiant de l'utilisateur
     * 
     * @var integer
     */
    public $id;

    /**
     * Login de l'utilisateur (adresse mail de l'utilisateur)
     * 
     * @var string
     */
    public $login;

    /**
     * Mot de passe de l'utilisateur
     * 
     * @var string
     */
    public $password;

    /**
     * Prénom de l'utilisateur
     * 
     * @var string
     */
    public $firstName;

    /**
     * Nom de l'utilisateur
     * 
     * @var string
     */
    public $lastName;

    /**
     * Statut de l'utilisateur
     * 
     * @var integer
     */
    public $status;

    /**
     * Date à laquelle ce utilisateur a été ajouté au système
     * 
     * @var \DateTime
     */
    public $dateCreate;

    /**
     * Date de la dernière connexion au système
     * 
     * @var \DateTime
     */
    public $lastLogin;

    /**
     * Adresse ip du poste à partir duquel l'utilisateur s'est connecté pour la dernière fois
     * 
     * @var string
     */
    public $lastIpLogin;

    /**
     * Date à laquell l'utilisateur s'est déconnecté pour la dernière fois
     * 
     * @var \DateTime
     */
    public $lastLogout;

    /**
     * Adresse ip du dernier poste où l'utilisateur s'est déconnété
     * 
     * @var string
     */
    public $lastIpLogout;

    /**
     * Profile de l'utilisateur
     * 
     * @var Profile
     */
    public $profile;

    /**
     * date de la dènière action de l'utilisateur
     * 
     * @var \DateTime
     */
    public $lastAction;

    /**
     * Détermine si l'utilisateur est connecté ou pas
     * 
     * @var boolean
     */
    public $isLogged;

    /**
     * Adresse mail de l'utilisateur
     * 
     * @var string
     */
    public $email;

    /**
     * Token de contrôle de l'action de l'utilisateur
     * 
     * @var string
     */
    public $opToken;

    /**
     * Temps de validité du token
     * 
     * @var \DateTime
     */
    public $tokenExpireDate;
    
    /**
     * Caisse dans laquelle travaille l'opérateur
     *
     * @var Caisse
     */
    public $caisse;

    /**
     * Retourne l'opérateur sous forme d'utilisateur connecté
     *
     * @return LoggedUser
     */
    public function toLoggedUser()
    {
        $loggeduser = new LoggedUser($this->login, $this->lastName, $this->firstName, $this->profile->code, $this->profile->description);
        $loggeduser->boxCode = $this->caisse->code;
        $loggeduser->boxKey = $this->caisse->key;
        $loggeduser->branchCode = $this->caisse->agence->code;
        $loggeduser->branchKey = $this->caisse->agence->key;
        $loggeduser->branchName = $this->caisse->agence->label;
        $loggeduser->boxIsOpened = $this->caisse->isOpened;
        $loggeduser->branchIsOpened = $this->caisse->agence->isOpened;
        $loggeduser->token = $this->opToken;
        $loggeduser->expireTokenDate = $this->tokenExpireDate;
        $loggeduser->status = $this->status;
        return $loggeduser;
    }

    /**
     * Permet de construire une instance de l'opérateur
     *
     * @param array $items
     * @return Operator
     */
    public static function buildInstance($items,$includeCaisse=true)
    {
        if (!is_array($items))
            return null;
        $instance = new Operator();
        $instance->id = isset($items['id']) ? $items['id'] : null;
        $instance->login = isset($items['login']) ? $items['login'] : null;
        $instance->password = isset($items['password']) ? $items['password'] : null;
        $instance->firstName = isset($items['firstName']) ? $items['firstName'] : null;
        $instance->lastName = isset($items['lastName']) ? $items['lastName'] : null;
        $instance->status = isset($items['status']) ? $items['status'] : null;
        $instance->lastIpLogin = isset($items['lastIpLogin']) ? $items['lastIpLogin'] : null;
        $instance->lastIpLogout = isset($items['lastIpLogout']) ? $items['lastIpLogout'] : null;
        $instance->isLogged = isset($items['isLogged']) ? $items['isLogged'] : null;
        $instance->email = isset($items['email']) ? $items['email'] : null;
        $instance->profile = isset($items['profile']) ? Profile::buildInstance($items['profile']) : null;
        $instance->opToken = isset($items['opToken']) ? $items['opToken'] : null;
        $instance->tokenExpireDate = isset($items['tokenExpireDate']) ? !is_null($items['tokenExpireDate']) ? new DateTime($items['tokenExpireDate']) : null : null;
        $instance->lastAction = isset($items['lastAction']) ? !is_null($items['lastAction']) ? new DateTime($items['lastAction']) : null : null;
        $instance->lastLogin = isset($items['lastLogin']) ? !is_null($items['lastLogin']) ? new DateTime($items['lastLogin']) : null : null;
        $instance->lastLogout = isset($items['lastLogout']) ? !is_null($items['lastLogout']) ? new DateTime($items['lastLogout']) : null : null;
        $instance->dateCreate = isset($items['dateCreate']) ? !is_null($items['dateCreate']) ? new DateTime($items['dateCreate']) : null : null;
        $instance->caisse = $includeCaisse? isset($items['caisse'])?Caisse::buildInstance($items['caisse'],false):null:null;
        return $instance;
    }
}
