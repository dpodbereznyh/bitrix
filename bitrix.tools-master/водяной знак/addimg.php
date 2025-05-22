<?

class WSimpleImage
{

	var $image;
	var $image_type;

	function load($filename)
	{
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
	{
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image, $filename, $compression);
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($this->image, $filename);
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($this->image, $filename);
		}
		if ($permissions != null) {
			chmod($filename, $permissions);
		}
	}

	function output($image_type = IMAGETYPE_JPEG)
	{
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($this->image);
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($this->image);
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($this->image);
		}
	}

	function getWidth()
	{
		return imagesx($this->image);
	}

	function getHeight()
	{
		return imagesy($this->image);
	}

	function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	function resizeToWidth($width)
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}

	function scale($scale)
	{
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}

	function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		imagecolortransparent($new_image,0x000000);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}

class WImgOverlayInit
{
	public $type;

	/**
	 * @param $uri - адрес изображения формата gif,jpeg,png
	 * @return bool|false|resource
	 */
	function loadImg($uri)
	{
		$this->type = exif_imagetype($uri);
		switch ($this->type) {
			case IMAGETYPE_GIF:
				$img = imagecreatefromgif($uri);
				break;
			case IMAGETYPE_JPEG:
				$img = imagecreatefromjpeg($uri);
				break;
			case IMAGETYPE_PNG:
				$img = imagecreatefrompng($uri);
				break;
		}
		if (!empty($img)) {
			imagealphablending($img, true);
			imagesavealpha($img, true);
			imagecolortransparent($img,0xffffff);
			return $img;
		}
		return false;
	}

	function saveImg(&$img, $uri)
	{
		switch ($this->type) {
			case IMAGETYPE_GIF:
				return imagegif($img, $uri);
			case IMAGETYPE_JPEG:
				return imagejpeg($img, $uri);
			case IMAGETYPE_PNG:
				return imagepng($img, $uri);
		}
	}

	/**
	 * фукнкция наложения на базовое изображение $base массива изображений $images
	 *
	 * @param       $baseUri - путь к подложке
	 * @param       $saveUri - итоговый путь куда сохранять результат
	 * @return bool - удача/неудача
	 */

	function image_overlay($baseUri, &$imgBase, $saveUri, $height = false, $width = false)
	{   //получаем основу
		if (!empty($baseUri)) {
			$baseImg['image'] = $this->loadImg($baseUri);
			if($baseImg['image']===false){return false;}
			$baseImg['height'] = ($height === false) ? imagesy($baseImg['image']) : $height;
			$baseImg['width'] = ($width === false) ? imagesx($baseImg['image']) : $width;
		}else{return false;}
		//получаем массив изображений
		$img=new WSimpleImage();
		$img->image=$imgBase->image;
		$img->image_type=$imgBase->image_type;
		$img->resizeToWidth((int)$baseImg['width']/2);
		$heightIcon=$img->getHeight();
		$widthIcon=$img->getWidth();
		$images[0]['image'] = $img->image;
		// Накладываем
		foreach ($images as &$itemImg) {
			if (empty($itemImg['image'])) continue;
			imagecopy($baseImg['image'], $itemImg['image'], $baseImg['width']-$widthIcon-$widthIcon*0.09, $baseImg['height']-$heightIcon*1.7, 0, 0, $baseImg['width'], $baseImg['height']);
			imagedestroy($itemImg);
		}
		// сохраняем изображение
		$result = $this->saveImg($baseImg['image'], $saveUri);
		imagedestroy($baseImg['image']);
		return $result;
	}
}
