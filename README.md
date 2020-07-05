
**PHP version - PHP 7.2.24-0ubuntu0.18.04.6**
```
composer install
```

**COMMAND USAGE
_1.run in root directory_
_2.vat optional parameter_
_3.you can provide additional currencies see example below_**

```
Example:  php bin/console import ~/Downloads/data.csv EUR:1,USD:0.987,GBP:0.878,BGN:0.567 BGN --vat=123456789
```
```
Output: Customer Vendor 1 1112.5765278922 BGN
```

**You can see the results inside store.txt in the root directory**

**UNIT TESTING**
Provided by CalculateTest.php 
I have two function getTotal and getTotalWithVat. 
They test the getTotal function in Calculate.php because, in order to get the right result - getTotal depends on all the functions in the class, so by testing it, I know that the other functions inside Calculate produce correct results.

**SIDE NOTE**

**This task was a challenge, because I had not developed/used:**
* commands in the cli with php,
* unit testing
* psr-4 autoloading

If possible, would really love to get specific feedback on the task no matter the outcome of my application. 

**Thank you for the opportunity!**

        
