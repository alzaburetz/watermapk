<?php
/**
 * Created by PhpStorm.
 * User: rashid
 * Date: 04.12.18
 * Time: 11:42
 */

//Класс с методами парсинга csv (Создать)
class ParseCSV {

}
require 'Watermarked.php';

$test = new Watermarked("test.jpg","watermark.png");
$test->loadpic();
$test->loadwatermark();
$test->changewatermark();
mkdir("tmp");
$test->putwatermark();

?>
