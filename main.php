<?php
/**
 * Created by PhpStorm.
 * User: rashid
 * Date: 04.12.18
 * Time: 11:42
 */

//Класс с методами парсинга csv (Создать)


require 'Watermark2.php';
$img_global_main;
$img_global;
$watermark_global = Methods::loadwatermark($argv[1]);
if (!file_exists("Photos")){
  mkdir("Photos");
}

if (!file_exists("More Photos")){
  mkdir("More Photos");
}


$file = fopen("Parsable.csv","r");
while (($line = fgetcsv($file)) == true) {
  //Обработка основной фотографии (Все работает)
  print_r($line);
  $url = "https://vsestiralnie.com".$line[1];
  $img_global_main = Methods::loadimage($url);
  Methods::putwatermark(Methods::changewatermark($watermark_global,$img_global_main), $img_global_main,"Photos/".basename($url));
  $more_ph = explode("||",$line[2]);
  print_r($more_ph);
  //Обработка остальных(костыли, не работает правильно)
  for ($i = 0; $i<count($more_ph); $i++) {
   $url = "https://vsestiralnie.com/".$more_ph[$i];
   if(file_exists($url)){
    $img_global = Methods::loadimage($url);
    Methods::putwatermark(Methods::changewatermark($watermark_global,$img_global), $img_global,"More Photos/".basename($url));
  } else continue;

  }
}



fclose($file);
?>
