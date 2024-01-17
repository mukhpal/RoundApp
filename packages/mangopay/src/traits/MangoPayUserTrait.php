<?php


namespace FraSim\MangoPay\Traits;


use FraSim\MangoPay\Models\MangoPayUser;

/**
 * Trait MangoPayUserTrait
 * @package FraSim\MangoPay\Traits
 * @property integer $id
 */
trait MangoPayUserTrait
{
    protected $_mangoPay;

    public function getMangoPayAttribute()
    {
        if(!isset($this->_mangoPay))
            $this->_mangoPay = new MangoPayUser($this);
        return $this->_mangoPay;
    }


}
