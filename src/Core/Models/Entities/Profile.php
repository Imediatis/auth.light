<?php
namespace Digitalis\Core\Models\Entities;

use DateTime;

/**
 * Profile Profil d'un utilisateur ou opérateur
 *
 * @copyright  2018 IMEDIATIS SARL http://www.imediatis.net
 * @license    Intellectual property rights of IMEDIATIS SARL
 * @version    Release: 1.0
 * @author     Sylvin KAMDEM<sylvin@imediatis.net> (Back-end Developper)
 */
class Profile 
{
    /**
     * Identifiant du profile
     * 
     * @var integer
     */
    public $id;

    /**
     * Code du profil
     * 
     * @var string
     */
    public $code;

    /**
     * Description du profil
     * 
     * @var string
     */
    public $description;

    /**
     * Statut du profil
     * 
     * @var boolean
     */
    public $status;

    /**
     * Date de création du profil
     * 
     * @var \DateTime
     */
    public $dateCreate;
    
    /**
     * Construit une instance de profile
     *
     * @param array $items
     * @return Profile
     */
    public static function buildInstance( $items)
    {
        if(!is_array($items))
            return null;
        $instance = new Profile();
        $instance->id = isset($items['id'])?$items['id']:null;
        $instance->code = isset($items['code'])?$items['code']:null;
        $instance->description = isset($items['description']) ? $items['description'] : null;
        $instance->status = isset($items['status']) ? $items['status'] : null;
        $instance->dateCreate = isset($items['dateCreate']) ? !is_null($items['dateCreate'])?new DateTime($items['dateCreate']):null : null;

        return $instance;
    }
}