PHP version - PHP 7.2.24-0ubuntu0.18.04.6 (cli) (built: May 26 2020 13:09:11)
*run  - composer install*

Command usage
  *run in root directory*
  *vat optional parameter*
  *you can provicde additional currencies see example below*
   Example:  php bin/console import ~/Downloads/data.csv EUR:1,USD:0.987,GBP:0.878,BGN:0.567 BGN --vat=123456789
   Output: Customer Vendor 1 1112.5765278922 BGN *in store.txt*
You can see the *RESULTS inside store.txt* in the root directory

Unit testing is provided by CalculateTest.php -
        - I have two function getTotal and getTotalWithVat. They test the getTotal function in Calculate.php because
          In order to get the right result - getTotal depends on all the functions in the class, so by testing it, I know that the other functions inside Calculate produce correct results.
*SIDE NOTE*

This task was a challenge, because I hadn't developed/used:
        commands in the cli with php,
        unit testing,
        psr-4 autoloading

*if possible* Would really love to get specific feedback on the task no matter the outcome of my application. 

Thank you for the opportunity!

        
