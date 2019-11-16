<?php

namespace Digitalis\Core\Models\DbAdapters;

use Digitalis\Core\Models\Data;
use Digitalis\Core\Models\Lexique;
use Digitalis\Core\Handlers\ErrorHandler;
use Digitalis\Core\Models\Entities\Operator;


/**
 * OperatorDbAdapter Gestionnaire en relation des base de données des utilisateurs des agences
 *
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM <sylvin@imediatis.net> (Back-end Developper)
 */
class OperatorDbAdapter
{
	/**
	 * enregistre les informations de dernière connexion de l'utilisateur
	 *
	 * @param string $login
	 * @return boolean
	 */
	public static function setLastLogin($login)
	{
		Data::sendData('operators/' . $login . '/lastlogin', null, "PUT");
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
		Data::sendData('operators/' . $login . '/lastlogout', null, "PUT");
		return true;
	}

	/**
	 * Permet de récupérer un utilisateur à partir de son login
	 *
	 * @param string $login
	 * @return Operator
	 */
	public static function getByLogin($login)
	{
		try {
			$repos = Data::getData('operators/' . $login);
			if (!isset($repos['data'])){
				if(isset($repos['message']) && strlen($repos['message'])>0)
					Data::setErrorMessage($repos['message']);
				return null;
			}
			return Operator::buildInstance($repos['data']);
		} catch (\Exception $exc) {
			Data::setErrorMessage(Lexique::GetString(CUR_LANG, an_error_occured));
			ErrorHandler::writeLog($exc);
		}
		return null;
	}

	public static function setLastAction($login)
	{
		Data::sendData('operators/'.$login.'/lastaction', null, "PUT");
		return false;
	}
	
	public static function genToken( $login)
	{
		$reponse = Data::sendData('operators/'.$login.'/token',null,"PUT");
		if(isset($reponse['data']) && $reponse['data'])
			return Operator::buildInstance($reponse['data']);
		return null;
	}
	
	public static function lockUserAccount( $login)
	{
		Data::sendData('operators/'.$login.'/lockaccount',null,"PUT");
		return true;
	}
}
