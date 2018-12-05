<?php

class Watermarked {
    protected $imgp;
    protected $watermarkp;
    protected $wx, $wy, $ix, $iy;
    protected $img;
    protected $watermark;

    public function __construct($imgpath, $watermarkpath)
    {
        $this->imgp = $imgpath;
        $this->watermarkp = $watermarkpath;
    }
    //Загрузка картинки (Пока один формат)
    function loadpic() {
      if (exif_imagetype("$this->imgp")==IMAGETYPE_JPEG) {
        $this->img = imagecreatefromjpeg("$this->imgp");
      } else {
        $this->img = imagecreatefrompng("$this->imgp");
      }
        imagelayereffect($this->img, IMG_EFFECT_REPLACE);
        imagealphablending($this->img ,true);
        echo "Loaded picture from $this->imgp\n";

    }
    //Загрузка вотермарки
    function loadwatermark() {
        $this->watermark = imagecreatefrompng("$this->watermarkp");
        echo "Loaded watermark from $this->watermarkp\n";
    }

    function getsizes() {
        $this->wx = imagesx($this->watermark);
        $this->wy = imagesy($this->watermark);
        $this->ix = imagesx($this->img);
        $this->iy = imagesy($this->img);
    }
    //Изменение вотермарки
    function changewatermark() {
        $width=imagesx($this->img)/2;
        $r=imagesx($this->img)/imagesy($this->img);
        $height=imagesy($this->img)/8*$r;
        echo "Previous size:\n\twidth:".imagesx($this->watermark)."\n\thieght:".imagesy($this->watermark)."\n";
        echo "Now:\n\twidth:$width\n\theight:$height\n";
        $watermark_resized = imagecreatetruecolor($width, $height);
        imagesavealpha($watermark_resized,true);
        $transparent = imagecolorallocatealpha($watermark_resized,0,0,0,127);
        imagecolortransparent($watermark_resized);
        imagefill($watermark_resized,0,0,$transparent);
        imagecopyresampled($watermark_resized,$this->watermark,0,0,0,0,$width,$height,imagesx($this->watermark),imagesy($this->watermark));
        $this->watermark = $watermark_resized;
    }
    //Наложение вотермарки (кастомизировать, но работает)
    function putwatermark() {
        $count = 0;
        while ($count<3) {
          $wmx = rand(0,imagesx($this->watermark));
          $miny = $count*imagesy($this->img)/3;
          echo $miny."\n";
          $maxy = ($count+1)*imagesy($this->img)/3-imagesy($this->watermark);
          echo "$maxy\n";
          $wmy = rand($miny,$maxy);
          imagecopy($this->img, $this->watermark,$wmx,$wmy,0,0,imagesx($this->watermark),imagesy($this->watermark));
          $count += 1;
          echo $wmx." ".$wmy."\n";
          echo $count."\n";
          $wmx = 0; $wmy = 0;
        }


        $date = new DateTime();
        $filename = $date->format('r');
        imagejpeg($this->img,"tmp/".$filename.".jpeg");
    }


}
