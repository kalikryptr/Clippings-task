<?php

namespace Tests\AppTest;


use PHPUnit\Framework\TestCase;
use Kromkata\Calculate\Calculate;
use Console\App\Currency;

class CalculateTest extends TestCase
{
    // private $calculator;

    // protected function setUp() :void
    // {
    //     // $this->calculator = new Currency('GBP', 0.878);
    // }

    // protected function tearDown() :void
    // {
    //     $this->calculator = NULL;
    // }
    public function testGetTotal()
    {
        $documentData = [
            [
              "Customer"=>"Vendor 1",
              "Vat number"=>"123456789",
              "Document Number"=>"1000000257",
              "Type"=>"1",
              "Parent Document"=>"",
              "Currency"=>"USD",
              "Total"=>"400",
            
            ],
            [
            
              "Customer"=>"Vendor 1",
              "Vat number"=>"123456789",
              "Document Number"=>"1000000260",
              "Type"=>"2",
              "Parent Document"=>"",
              "Currency"=>"EUR",
              "Total"=>"100",
            ],
            [
              "Customer"=>"Vendor 1",
              "Vat number"=>"123456789",
              "Document Number"=>"1000000261",
              "Type"=>"3",
              "Parent Document"=>"",
              "Currency"=>"GBP",
              "Total"=>"50",
            ],
            [
              "Customer"=>"Vendor 1",
              "Vat number"=>"123456789",
              "Document Number"=>"1000000264",
              "Type"=>"1",
              "Parent Document"=>"",
              "Currency"=>"EUR",
              "Total"=>"1600"
            ],
            [
                "Customer"=>"Vendor 2",
                "Vat number"=>"987654321",
                "Document Number"=>"1000000258",
                "Type"=>"1",
                "Parent Document"=>"",
                "Currency"=>"EUR",
                "Total"=>"900",
              ],
              [
                "Customer"=>"Vendor 2",
                "Vat number"=>"987654321",
                "Document Number"=>"1000000262",
                "Type"=>"2",
                "Parent Document"=>"1000000258",
                "Currency"=>"USD",
                "Total"=>"200",
              ],
              [
                "Customer"=>"Vendor 3",
                "Vat number"=>"123465123",
                "Document Number"=>"1000000259",
                "Type"=>"1",
                "Parent Document"=>"",
                "Currency"=>"GBP",
                "Total"=>"1300",
              ],
              [
                "Customer"=>"Vendor 3",
                "Vat number"=>"123465123",
                "Document Number"=>"1000000263",
                "Type"=>"3",
                "Parent Document"=>"1000000259",
                "Currency"=>"EUR",
                "Total"=>"100",
              ]

            ];
    
            $compare = [
                "Vendor 1"=>1722.8257345491,
                "Vendor 2"=>612.28713272543,
                "Vendor 3"=>1387.8
            ];
    
            $currencyList = [new Currency("GBP",0.878),new Currency("EUR",1), new Currency("USD",0.987)];
            $result = new Calculate($currencyList, $documentData, "EUR", null, "GBP");
            // $result->filterByVat();
            $result->documentToCurrency();
            $totalInDefaultCurrency = $result->getTotal();
            
            $result->documentToOutputCurrency($totalInDefaultCurrency);
            $this->assertEquals($compare, $result->getDocument());
    }
    public function testGetTotalWithVat()
    {
        $documentData = [
        [
          "Customer"=>"Vendor 1",
          "Vat number"=>"123456789",
          "Document Number"=>"1000000257",
          "Type"=>"1",
          "Parent Document"=>"",
          "Currency"=>"USD",
          "Total"=>"400",
        
        ],
        [
        
          "Customer"=>"Vendor 1",
          "Vat number"=>"123456789",
          "Document Number"=>"1000000260",
          "Type"=>"2",
          "Parent Document"=>"",
          "Currency"=>"EUR",
          "Total"=>"100",
        ],
        [
          "Customer"=>"Vendor 1",
          "Vat number"=>"123456789",
          "Document Number"=>"1000000261",
          "Type"=>"3",
          "Parent Document"=>"",
          "Currency"=>"GBP",
          "Total"=>"50",
        ],
        [
          "Customer"=>"Vendor 1",
          "Vat number"=>"123456789",
          "Document Number"=>"1000000264",
          "Type"=>"1",
          "Parent Document"=>"",
          "Currency"=>"EUR",
          "Total"=>"1600"
          ]
        ];

        $compare = [
            "Vendor 1"=>1722.8257345491,
        ];
        $currencyList = [new Currency("EUR",1),new Currency("GBP",0.878), new Currency("USD",0.987)];
        // $currencyList, $document->getData(), $defaultCurrencyCode, $vatFilter, $outputCurrency
        $result = new Calculate($currencyList, $documentData, "EUR", 123456789, "GBP");
        $result->filterByVat();
        $result->documentToCurrency();
        $totalInDefaultCurrency = $result->getTotal();
       
        $result->documentToOutputCurrency($totalInDefaultCurrency);
        $this->assertEquals($compare, $result->getDocument());
    }
}
