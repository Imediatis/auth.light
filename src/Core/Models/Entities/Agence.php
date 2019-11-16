<?php
namespace Digitalis\Core\Models\Entities;

use DateTime;

/**
 * Agence 
 *
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM<sylvin@imediatis.net> (Back-end Developper)
 */
class Agence 
{
    /**
     * Identifiant de l'agence
     * 
     * @var integer
     */
    public $id;

    /**
     * Code de l'agence
     * 
     * @var string
     */
    public $code;

    /**
     * Nom de l'agence
     * 
     * @var string
     */
    public $label;

    /**
     * Adresse de l'agence
     * 
     * @var string
     */
    public $address;

    /**
     * Numéro de téléphone de l'agence
     * 
     * @var string
     */
    public $phone1;

    /**
     * Deuxième numéro de téléphone de l'agence
     * 
     * @var string
     */
    public $phone2;

    /**
     * Adresse mail de l'agence
     * 
     * @var string
     */
    public $email;

    /**
     * Clé d'identification de l'agence (nécessaire lors de l'opération d'ouverture de celle-ci)
     * 
     * @var string
     */
    public $key;

    /**
     * Détermine si l'agences ouverte ou pas
     * 
     * @var boolean
     */
    public $isOpened;

    /**
     * Statut de l'agence
     * 
     * @var integer
     */
    public $statut;

    /**
     * Entreprise à laquelle appartient l'agence
     * 
     * @var string
     */
    public $entreprise;

    /**
     * Ville où se trouve l'agence
     * 
     * @var string
     */
    public $city;

    /**
     * Date de création de l'agence
     *
     * @var \DateTime
     */
    public $dateCreate;

    
    public static function buildInstance( $items)
    {
        if(!is_array($items))
        return null;
        $instance = new Agence();
        $instance->id = isset($items['id'])?$items['id']:null;
        $instance->code = isset($items['code'])?$items['code']:null;
        $instance->label = isset($items['label']) ? $items['label'] : null;
        $instance->address = isset($items['address']) ? $items['address'] : null;
        $instance->phone1 = isset($items['phone1']) ? $items['phone1'] : null;
        $instance->phone2 = isset($items['phone2']) ? $items['phone2'] : null;
        $instance->email = isset($items['email']) ? $items['email'] : null;
        $instance->key = isset($items['key']) ? $items['key'] : null;
        $instance->isOpened = isset($items['isOpened']) ? $items['isOpened'] : null;
        $instance->statut = isset($items['statut']) ? $items['statut'] : null;
        $instance->entreprise = isset($items['entreprise']) ? $items['entreprise'] : null;
        $instance->city = isset($items['city']) ? $items['city'] : null;
        $instance->dateCreate = isset($items['dateCreate']) ? !is_null($items['dateCreate'])? new DateTime($items['dateCreate']):null : null;

        return $instance;
    }

}