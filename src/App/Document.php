<?php

namespace Console\App;


use Console\App\Calculate;
use Console\App\Currency;

/*
    This class:
        reads the data from the csv file
        validates based on the rules in the application README
*/

class Document
{
    private $data = [];

    function __construct($file)
    {
        if ($this->validate($file)) {
            
            $this->data = $this->parseCsv($file);
            $this->checkParents();
        }
    }

    protected function checkParents()
    {
        $parents = [];
        $documentN = [];
        foreach ($this->data as $value) {
            if (!empty($value['Parent Document'])) {
                $parents[] = $value['Parent Document'];
            }
            $documentN[] = $value['Document Number'];
        }

        foreach ($parents as $parent) {
            if (!in_array($parent, $documentN)) {
                throw new \Exception(sprintf('Parent %s could not be found!', $parent));
            }
        }

    }

    protected function validate($file)
    {
        if (file_exists($file)) {
            return true;
        } else {
            throw new \Exception('File does not exist!');
        }
    }
    
    protected function parseCsv($file)
    {
        $columns = ["Customer", "Vat number", "Document Number", "Type", "Parent Document", "Currency", "Total"];
        $picked     = array();
        $map        = array();

        $return = [];
        $handle = fopen($file, "r");
        if (FALSE !== $handle) {
            $row = fgetcsv($handle, 1000, ',');

            foreach ($columns as $name) {
                $index = array_search(strtolower($name), array_map('strtolower', $row));
                if (FALSE !== $index) {
                    $map[$index] = $name;
                }
            }

            while ($data = fgetcsv($handle, 1000, ",")) {
                $row     = array();

                foreach ($map as $index => $field) {
                    $row[$field] = $data[$index];
                }

                $return[] = $row;
            }

            fclose($handle);
        }
        return $return;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }
}
