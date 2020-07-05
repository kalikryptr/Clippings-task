<?php

namespace Kromkata\Calculate;

class Calculate
{

    private $types = [
        1 => "invoice",
        2 => "credit note",
        3 => "debit note"
    ];
    private $currencyList = [];
    private $document = [];
    private $defaultCurrencyCode = "";
    private $vatFilter = "";
    private $outputCurrency = "";

    function __construct($currencyList, $document, $defaultCurrencyCode, $filter, $outputCurrency)
    {
        $this->currencyList = $currencyList;
        $this->document = $document;
        $this->defaultCurrencyCode = $defaultCurrencyCode;
        $this->vatFilter = $filter;
        $this->outputCurrency = $outputCurrency;
    }

    /*
        Converts document data to a currency
        
        this function is used to convert data to defaultCurrency - divide
        this function is used to convert data to outputCurrency - multiply
    */
    public function documentToCurrency($mode = 'divide')
    {
        $tmp = $this->document;
        foreach ($tmp as $key => &$value) 
        {
            foreach ($this->currencyList as $currency) 
            {
                    if($mode == 'divide')
                    {
                        if ($this->defaultCurrencyCode != $value['Currency'] && $value['Currency'] == $currency->getcurrencyCode()) 
                        {
                                $value['Total'] = $value['Total'] / $currency->getValue();
                                $value['Currency'] = $this->defaultCurrencyCode;
                        }
                    }
                    else if($mode == 'multiply')
                    {
                        if($this->defaultCurrencyCode == $currency->getcurrencyCode())
                        {
                            $value = $value * $currency->getValue();
                        }
                    }
            }
        }
        $this->setDocument($tmp);
        unset($tmp);
    }
    
    /*
        Converts resultset to final output currency
    */
    public function documentToOutputCurrency($data)
    {   
           $this->setDocument($data);
           $this->defaultCurrencyCode = $this->outputCurrency; // because we use defaultCurrencyCode in documentToCurrency()
           $mode = "multiply"; // change mode so we can multiply
           $this->documentToCurrency($mode);

           return $this->getDocument();
    }
    /*
        Filter document data based on vat
    */
    public function filterByVat()
    {
        $doc = $this->getDocument();
        if(!empty($this->vatFilter))
        {
            foreach ($doc as $key => $value) 
            {
                    if ($value['Vat number'] != $this->vatFilter) 
                    {
                        unset($doc[$key]);

                    }
            }
        }
        if(empty($doc))
        {
            throw new \Exception(sprintf('No document with vat number:%s have been found!', $this->vatFilter));

        }
        $this->setDocument($doc);
        unset($doc);
    }

    /*
        Sums the total based on the types - invoice, credit, debit
    */
    public function getTotal()
    {
        $return = [];
        foreach ($this->document as $value) 
        {
            if (!array_key_exists($value['Customer'], $return)) 
            {
                $return[$value['Customer']] = 0;
            }
            switch ($this->types[$value['Type']]) 
            {
                case 'invoice':
                    $return[$value['Customer']] += $value['Total'];
                    #sum
                    break;
                case 'credit note':
                    $return[$value['Customer']] -= $value['Total'];
                    # minus
                    break;
                case 'debit note':
                    $return[$value['Customer']]  += $value['Total'];
                    # sum
                    break;
            }
        }
        return $return;
    }

    /**
     * Get the value of document
     */ 
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set the value of document
     *
     * @return  self
     */ 
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }
}
