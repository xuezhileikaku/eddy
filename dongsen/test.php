<?php
/**
 * xxx file.
 *
 * @author Eddy <eddy@rrgod.com>
 * @link http://www.rrgod.com/
 * @copyright Copyright &copy; 20012-2013 Eddy Blog
 * @license http://www.rrgod.com Eddy Blog
 * @version 1.0
 */

include "Snoopy.class.php";
include "config.php";
$snoopy = new Snoopy;
$snoopy->fetch($hostaddr);
var_dump($snoopy);
?>