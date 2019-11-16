<?php

namespace Digitalis\Core\Controllers;

use DateTime;
use Digitalis\Core\Models\ViewModels\LoginOperatorViewModel;
use Digitalis\Core\Controllers\ApiController;
use Digitalis\Core\Handlers\ErrorHandler;
use Digitalis\Core\Models\ApiResponse;
use Digitalis\Core\Models\Data;
use Digitalis\Core\Models\DbAdapters\OperatorDbAdapter;
use Digitalis\Core\Models\DbAdapters\UserDbAdapter;
use Digitalis\Core\Models\Lexique;
use Digitalis\Core\Models\SessionManager;
use Digitalis\Core\Models\SysConst;
use Digitalis\Core\Models\ViewModels\LoginUserViewModel;
use Imediatis\EntityAnnotation\ModelState;
use Imediatis\EntityAnnotation\Security\InputValidator;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * AccountApiController Description of AccountApiController here
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM <sylvin@imediatis.net> (Back-end Developper)
 */
class AuthApiController extends ApiController
{
	public function __construct(Container $container)
	{
		parent::__construct($container);
		parent::setCurrentController(__class__);
	}

	public function logOperator(Request $request, Response $response)
	{
		$model = new LoginOperatorViewModel();
		$model = InputValidator::BuildModelFromRequest($model, $request);
		$output = new ApiResponse();
		$currentTime = new DateTime();
		$actualIp = SessionManager::get(SysConst::ORIGINAL_CLIENT_IP);
		try {
			if (ModelState::isValid()) {
				$operator = OperatorDbAdapter::getByLogin(base64_encode($model->login));
				if ($operator) {
					OperatorDbAdapter::setLastAction(base64_encode($operator->login));

					if ($operator->status == 2 || $operator->status == 0) {
						$output->message = $operator->status == 2 ? "Compte non actif" : $operator->status == 0 ? "Compte bloqué" : null;
						OperatorDbAdapter::setLastLogout(base64_encode($operator->login));
						$output->data = $operator->toLoggedUser();
						$output->found = true;
						goto END;
					}

					$lastIp = $operator->lastIpLogin;
					if(!is_null($operator->lastAction)){
						$diff =  $currentTime->diff($operator->lastAction);
						if($actualIp !== $lastIp && $diff->h<24){
							OperatorDbAdapter::lockUserAccount(base64_encode($operator->login));
							$output->message = "Compte d'utilisateur blocqué: Veuillez contacter l'administrateur";
							$output->found = false;
							$output->code = -1;
							$operator->status = 0;
							$output->data = $operator->toLoggedUser();
							goto END;
						}
					}
					if (password_verify($model->pwd, $operator->password)) {

						if ($operator->isLogged) {
							$laction = $operator->lastAction;
							if ($laction) {
								$now = new \DateTime();
								$diff = $now->diff($laction);
								if ($diff->i < 15) {
									OperatorDbAdapter::setLastLogout(base64_encode($operator->login));
									OperatorDbAdapter::setLastAction(base64_encode($operator->login));
								} else {
									$output->message = "Déconnexion reconnexion";
									OperatorDbAdapter::setLastLogout(base64_encode($operator->login));
									goto LOGGUSER;
								}
							} else {
								OperatorDbAdapter::setLastLogout(base64_encode($operator->login));
							}
						} else {
							LOGGUSER: if ($operator->caisse) {
								if (strcmp($operator->caisse->key, $model->boxKey) == 0) {
									$rcurl = OperatorDbAdapter::genToken(base64_encode($operator->login));
									if ($rcurl) {
										$output->found = true;
										$output->data = $rcurl->toLoggedUser();
										OperatorDbAdapter::setLastLogin(base64_encode($operator->login));
									}
								}
							}
						}
					} else {
						$output->message = "Mot de passe invalide";
					}
				} else {
					$output->message = Data::getErrorMessage();
					if ($output->message == "Unauthorized action:token" || $output->message == "Invalid request token")
						$output->code = 401;
				}
			} else {
				$output->message = ApiResponse::ERROR_MODELSTATE;
				$output->modelstateerror = ModelState::getErrors();
			}
		} catch (\Exception $exc) {
			Data::setErrorMessage(Lexique::GetString(CUR_LANG, an_error_occured));
			ErrorHandler::writeLog($exc);
			$output->message = Lexique::GetString(CUR_LANG, an_error_occured);
			$output->status = false;
			$output->data = null;
		}
		END: return $this->render($response, $output);
	}

	public function logUser(Request $request, Response $response)
	{
		$model = new LoginUserViewModel();
		$model = InputValidator::BuildModelFromRequest($model, $request);
		$output = new ApiResponse();
		$currentTime = new DateTime();
		$actualIp = SessionManager::get(SysConst::ORIGINAL_CLIENT_IP);
		try {
			if (ModelState::isValid()) {
				$user = UserDbAdapter::getByLogin(base64_encode($model->email));
				if ($user) {
					UserDbAdapter::setLastAction(base64_encode($user->login));

					if($user->status==2 || $user->status == 0){
						$output->message = $user->status == 2 ? "Compte non actif":$user->status ==0?"Compte bloqué":null;
						UserDbAdapter::setLastLogout(base64_encode($user->login));
						$output->data = $user->toLoggedUser();
						$output->found = true;
						goto END;
					}

					$lastIp = $user->lastIpLogin;
					if (!is_null($user->lastAction)) {
						$diff =  $currentTime->diff($user->lastAction);
						if ($actualIp !== $lastIp && $diff->h < 24) {
							UserDbAdapter::lockUserAccount(base64_encode($user->login));
							$output->message = "Compte d'utilisateur blocqué: Veuillez contacter l'administrateur";
							$output->found = false;
							$output->code = -1;
							$user->status = 0;
							$output->data = $user->toLoggedUser();
							goto END;
						}
					}
					if (password_verify($model->password, $user->password)) {
						if ($user->isLogged) {
							$laction = $user->lastAction;
							if ($laction) {
								$now = new \DateTime();
								$diff = $now->diff($laction);
								if ($diff->i < 15) {
									$output->message = "Deconnexion";
									UserDbAdapter::setLastLogout(base64_encode($user->login));
								} else {
									$output->message = "Reconnexion";
									UserDbAdapter::setLastLogout(base64_encode($user->login));
									goto LOGGUSER;
								}
							} else {
								UserDbAdapter::setLastLogout(base64_encode($user->login));
								$output->message = "Deconnexion";
							}
						} else {
							LOGGUSER: $rcurl = UserDbAdapter::genToken(base64_encode($user->login));
							if ($rcurl) {
								$output->found = true;
								$output->data = $user->toLoggedUser();
								UserDbAdapter::setLastLogin(base64_encode($user->login));
							} else {
								$output->message = Data::getErrorMessage();
								$output->code = 404;
							}
						}
					} else {
						$output->message = "Invalide mot de passe";
					}
				} else {
					$output->message = Data::getErrorMessage();
					if ($output->message == "Unauthorized action:token" || $output->message == "Invalid request token")
						$output->code = 401;
				}
			} else {
				$output->message = ApiResponse::ERROR_MODELSTATE;
				$output->modelstateerror = ModelState::getErrors();
			}
		} catch (\Exception $exc) {
			Data::setErrorMessage(Lexique::GetString(CUR_LANG, an_error_occured));
			ErrorHandler::writeLog($exc);
			$output->message = "slslsl " . Lexique::GetString(CUR_LANG, an_error_occured);
			$output->status = false;
			$output->data = null;
		}
		END: return $this->render($response, $output);
	}
}
