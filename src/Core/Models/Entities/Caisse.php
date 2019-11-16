<?php
namespace Digitalis\Core\Models\Entities;

use DateTime;

/**
 * Caisse 
 *
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM<sylvin@imediatis.net> (Back-end Developper)
 */
class Caisse 
{
    /**
     * Identifiant de la caisse
     * 
     * @var integer
     */
    public $id;

    /**
     * code de la caisse découle du code de l'agence
     * 
     * @var string
     */
    public $code;

    /**
     * Clé de sécurité de la caisse
     * 
     * @var string
     */
    public $key;

    /**
     * Statut de la caisse
     * 
     * @var integer
     */
    public $status;

    /**
     * Détermine si la caisse est ouverte ou pas
     * 
     * @var boolean
     */
    public $isOpened;

    /**
     * Date de création de la caisse
     * 
     * @var \DateTime
     */
    public $dateCreate;

    /**
     * Personne qui crée la caisse
     * 
     * @var string
     */
    public $userCreate;

    /**
     * Opérateur affecté à cette caisse
     * 
     * @var Operator
     */
    public $operator;

    /**
     * Agence à laquelle appartient la caisse
     * @var Agence
     */
    public $agence;

    /**
     * Montant maximal journalier pour la caisse
     * 
     * @var integer
     */
    public $maxDailyAmount;

    
    public static function buildInstance( $items, $includeOperator=true)
    {
        if(!is_array($items))
        return null;
        $instance = new Caisse();
        $instance->id = isset($items['id'])?$items['id']:null;
        $instance->code = isset($items['code'])?$items['code']:null;
        $instance->key = isset($items['key']) ? $items['key'] : null;
        $instance->status = isset($items['status']) ? $items['status'] : null;
        $instance->isOpened = isset($items['isOpened']) ? $items['isOpened'] : null;
        $instance->userCreate = isset($items['userCreate']) ? $items['userCreate'] : null;
        $instance->maxDailyAmount = isset($items['maxDailyAmount']) ? $items['maxDailyAmount'] : null;
$instance->agence = isset($items['agence'])? Agence::buildInstance($items['agence']):null;
        $instance->dateCreate = isset($items['dateCreate']) ? !is_null($items['dateCreate'])?new DateTime($items['dateCreate']):null : null;
        $instance->operator = $includeOperator?( isset($items['operator'])?Operator::buildInstance($items['operator'],false):null):null;

        return $instance;
    }

}