<?php
class Methods {
//Загрузка картинки с пути (.png, .jp(e)g) возвращает ресурс - картинку
static function loadimage($path) {
  if (exif_imagetype("$path")==IMAGETYPE_JPEG) {
    $image = imagecreatefromjpeg("$path");
  } else {
    $imgage = imagecreatefrompng("$path");
  }
  imagelayereffect($image, IMG_EFFECT_NORMAL);
  imagealphablending($image ,true);
  echo "Loaded picture from $path\n";
  return $image;
}
//Загрузка ватермарки с пути, возвращает реурс - ватермарку
static function loadwatermark($path) {
    $watermark = imagecreatefrompng("$path");
    echo "Loaded watermark from $path\n";
    return $watermark;
}
//Изменение размера ватермарки в половину ширины картинки
static function changewatermark($watermark, $image) {
    $width=imagesx($image)/2;
    $r=imagesx($image)/imagesy($image);
    $height=imagesy($image)/8*$r;
    $watermark_resized = imagecreatetruecolor($width, $height);
    imagesavealpha($watermark_resized,true);
    $transparent = imagecolorallocatealpha($watermark_resized,0,0,0,127);
    imagecolortransparent($watermark_resized);
    imagefill($watermark_resized,0,0,$transparent);
    imagecopyresampled($watermark_resized,$watermark,0,0,0,0,$width,$height,imagesx($watermark),imagesy($watermark));
    return $watermark_resized;
}
//Наложение ватермарки
static function putwatermark($watermark, $image, $path) {
    $count = 0;
    while ($count<3) {
      $minx = imagesx($image)/5;
      $wmx = rand($minx,imagesx($watermark)-$minx);
      $wmy = rand($count*imagesy($image)/3,($count+1)*imagesy($image)/3-imagesy($watermark));
      imagecopy($image, $watermark,$wmx,$wmy,0,0,imagesx($watermark),imagesy($watermark));
      $count += 1;
    }
    imagejpeg($image,$path);
    echo "Picture watermarked to $path\n";
}
}
?>
