<?php
/**
 * Created by PhpStorm.
 * User: rashid
 * Date: 04.12.18
 * Time: 15:04
 */

class Watermarked {
    protected $imgp;
    protected $watermarkp;
    protected $finalized;
    protected $thumbnailp;

    public $img;
    public $thumbnail;
    public $watermark;

    public function __construct($imgpath, $watermarkpath)
    {
        $this->imgp = $imgpath;
        $this->watermarkp = $watermarkpath;
    }

    //Загрузка картинки (Пока один формат)
    function loadpic() {
      if (exif_imagetype("$this->imgp")==IMAGETYPE_JPEG) {
        $this->img = imagecreatefromjpeg("$this->imgp");
        imagelayereffect($this->img, IMG_EFFECT_REPLACE);
        imagealphablending($this->img ,true);
        echo "Loaded picture from $this->imgp\n";
      } else {
        $this->img = imagecreatefrompng("$this->imgp");
        imagelayereffect($this->img, IMG_EFFECT_REPLACE);
        imagealphablending($this->img ,true);
        echo "Loaded picture from $this->imgp\n";
      }
    }
    //Загрузка вотермарки
    function loadwatermark() {
        $this->watermark = imagecreatefrompng("$this->watermarkp");
        echo "Loaded watermark from $this->watermarkp\n";
    }

    //Изменение вотермарки
    function changewatermark() {
        $width=imagesx($this->img)/2;
        $height=imagesx($this->img)/8;
        echo "Previous size:\n\twidth:".imagesx($this->watermark)."\n\thieght:".imagesy($this->watermark)."\n";
        echo "Now:\n\twidth:$width\n\theight:$height\n";
        $watermark_resized = imagecreatetruecolor($width, $height);
        imagesavealpha($watermark_resized,true);
        $transparent = imagecolorallocatealpha($watermark_resized,0,0,0,127);
        imagecolortransparent($watermark_resized);
        imagefill($watermark_resized,0,0,$transparent);
        imagecopyresampled($watermark_resized,$this->watermark,
                          0,0,0,0,$width,$height,imagesx($this->watermark),imagesy($this->watermark));
        //imagepng($watermark_resized, 'watermark_test.png');
        $this->watermark = $watermark_resized;
    }
    //Наложение вотермарки (кастомизировать, но работает)
    function putwatermark() {
        $imgwidth = imagesx($this->img);
        $imgheight = imagesy($this->img);
        $wmarkwidth = imagesx($this->watermark);
        $wmarkheight = imagesy($this->watermark);
        //imagecopy($this->img, $this->watermark,0,0,0,0,$wmarkwidth,$wmarkheight);
        $c = 0;
        $Coordinates = array(array(rand(0,imagesx($this->img)),rand(0,imagesy($this->img))),);
        echo $Coordinates[0][0]."\n";
        echo $Coordinates[0][1]."\n";
        while ($c < 3) {
              $rand_x = rand(0,imagesx($this->img)-imagesx($this->watermark));
              $rand_y = rand(0,imagesy($this->img)-imagesx($this->watermark));
              if ($rand_x+$Coordinates[$c][0] < imagesx($this->watermark) &&
                  $rand_y+$Coordinates[$c][1] < imagesy($this->watermark)) {
                    continue;
                } else {
                  $ar = array($rand_x, $rand_y);
                  echo "Will push ".$ar[0]." ".$ar[1]."\n";
                  array_push($Coordinates, $ar);
                  $c += 1;
                }
        }
        $c = 0;
        while ($c < 3) {
          imagecopy($this->img, $this->watermark,$Coordinates[$c][0],$Coordinates[$c][1],0,0,$wmarkwidth,$wmarkheight);
          $c += 1;
        }
        //imagecopy($this->img, $this->watermark,0,0,0,0,$wmarkwidth,$wmarkheight);
        $date = new DateTime();
        $filename = $date->format('r');
        imagejpeg($this->img,"tmp/".$filename.".jpeg");
    }


}
