<?php
namespace Console\App;

class Currency
{
    private $value = "";
    private $currencyCode = "";

    function __construct($currencyCode, $value)
    {
        $this->validate($value);
        $this->value = $value;
        $this->currencyCode = $currencyCode;
    }


    private function validate($value)
    {

        if( (float) $value < 0) 
        {
            throw new \Exception(sprintf('Value is negative!', $value));

        }
    }

    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

  

    /**
     * Get the value of currencyCode
     */ 
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }
}

