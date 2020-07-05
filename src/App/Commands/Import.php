<?php

namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Console\App\Document;
use Console\App\Currency;
use Kromkata\Calculate\Calculate;

class Import extends Command
{
    public $data = [];
    public $currencyList = [];
    public $types = [];

    protected function configure()
    {
        $this->setName('import')
            ->setDescription('imports!')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.')
            ->addArgument('file', InputArgument::REQUIRED, 'Pass file to import.')
            ->addArgument('currency', InputArgument::REQUIRED, 'Pass currency list with exchange rate')
            ->addArgument('outputCurrency', InputArgument::REQUIRED, 'Pass output currency')
            ->addOption('vat', null, InputArgument::OPTIONAL, 'Filter data by vat number');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $currencyString = $input->getArgument('currency');
        $outputCurrency = $input->getArgument('outputCurrency');
        $vatFilter = $input->getOption('vat');
        $document = new Document($file);

        $currencies = explode(',', $currencyString);
        $currencyList = [];
        $defaultCurrencyCode = "";

        foreach ($currencies as $currency) 
        {
            $curr = explode(':', $currency);
            $symbol = $curr[0];
            $value = $curr[1];
            if ($value == 1) $defaultCurrencyCode = $symbol;
            $currencyList[] = new Currency($symbol, $value);
        }

        $this->validate($currencyList, $outputCurrency); // check if outputCurrency is in Currency instances

        $calculate = new Calculate($currencyList, $document->getData(), $defaultCurrencyCode, $vatFilter, $outputCurrency);
        $calculate->filterByVat();
        $calculate->documentToCurrency();
        $totalInDefaultCurrency = $calculate->getTotal();

        if($outputCurrency == $defaultCurrencyCode) // skip conversion if EUR == EUR
        {
            $calculate->setDocument($totalInDefaultCurrency);
        }
        else
        {
            $calculate->documentToOutputCurrency($totalInDefaultCurrency);
        }
        $this->store($calculate->getDocument(), $outputCurrency);
        return 0;
    }

    private function validate($currencies, $outputCurrency)
    { 
        $supported = [];
        foreach($currencies as $currency)
        {   
            $supported[] = $currency->getcurrencyCode();
        }

        if(!in_array($outputCurrency, $supported))
        {
                throw new \Exception(sprintf('No document with currency code:%s has been found!', $outputCurrency));
        }
    }

    private function store($data, $outputCurrency)
    {
        $stringData = "";
        foreach($data as $key => $value)
        {
            $stringData .= "Customer ".$key." ".$value." ".$outputCurrency.PHP_EOL;
        }
        if(!file_exists('./store.txt')){
            $file = fopen("./store.txt","w");
        }
        else
        {
            $file = fopen("./store.txt","a");
        }
        fwrite($file,$stringData.PHP_EOL);
        fclose($file);
    }
}
