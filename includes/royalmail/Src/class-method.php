<?php
namespace WPRubyRoyalMail\Includes\RoyalMail\Src;

class Method
{
    // The shipping code name of the method
    public $shippingMethodName;

    // The clean shipping method name of the shipping method
    public $shippingMethodNameClean;

    // The country code of the method
    public $countryCode;

    // The method price
    public $methodPrice;

    // The maximum insurance value the method has available
    public $insuranceValue;

    // The minimum weight the shipping method can accommodate
    public $minimumWeight;

    // The maximum weight the shipping method can accommodate
    public $maximumWeight;

    // The parcel size, only applies to small and medium parcels
    public $size;
}
