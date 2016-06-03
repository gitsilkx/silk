<?php

/**
 * This is Image component to resize image
 */
class ImageComponent extends Component {

    var $cacheDir = 'imagecache'; // relative to $uploadDir path
    var $uploadDir = 'uploads/';

    /**
     * Automatically upload an image and returns new image name
     *
     * @param string $oldImageName old image file name to delete it from server.
     * @param array $imageArray Full image array.
     * @param string $imagePath Path to the image file.
     * @param string $rename Image new name, default is null
     * @return mixed    Either string or false, depends on valid image.
     * @access public
     */
    public function upload($oldImageName = null, $imageArray, $imagePath, $rename = null) {
        //pr($imageArray);exit;

        if (is_uploaded_file($imageArray['tmp_name'])) {

            $filename = explode('.', $imageArray['name']);
            $ext = strtolower(end($filename));
            $imageArray['name'] = time() . ((!is_null($rename)) ? $rename . '.' . $ext : str_replace(" ", "-", $imageArray['name']));

            if ($ext === 'png' || $ext === 'jpeg' || $ext === 'jpg') {
                if (!is_dir($imagePath)) {
                    mkdir($imagePath, 0777);
                }
                if (!is_null($oldImageName)) {
                    $this->delete($oldImageName, $imagePath);
                }

                move_uploaded_file($imageArray['tmp_name'], $imagePath . '/' . $imageArray['name']);
            } else {
                echo 'Invalid image type. Upload only .png,.jpeg,.jpg image';
               // $this->Session->setFlash('Invalid image type. Upload only .png image', 'success');
              //  $this->Session->setFlash("Invalid image type. Upload only .png image");
                return false;
            }
        } else {
            echo 'Empty image file';
           // $this->Session->setFlash(__("Empty image file"));
            return false;
        }
        return $imageArray['name'];
    }

    /**
     * Delete the image from folder
     *
     * @param string $image image file name to delete it from server.
     * @param string $path Path to the image file.
     * @return mixed    Either true or false, depends on delete.
     * @access public
     */
    public function delete($image, $path) {
        if (!empty($image) && !empty($path)) {
            $oldImage = $path . '/' . $image;
            if (file_exists($oldImage))
                if (unlink($oldImage))
                    return true;
        }
        return false;
    }

    /**
     * Automatically resizes an image and returns formatted IMG tag
     *
     * @param string $path Path to the image file, relative to the webroot/img/ directory.
     * @param integer $width Image of returned image
     * @param integer $height Height of returned image
     * @param boolean $aspect Maintain aspect ratio (default: true)
     * @param array    $htmlAttributes Array of HTML attributes.
     * @param boolean $return Wheter this method should return a value or output it. This overrides AUTO_OUTPUT.
     * @return mixed    Either string or echos the value, depends on AUTO_OUTPUT and $return.
     * @access public
     */
    function resize($path, $width, $height, $aspect = true, $crop = false, $cropvars = array(), $autocrop = false, $htmlAttributes = array(), $return = false, $webrootPath, $onlyPath = false) {

        $types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp"); // used to determine image type

        $fullpath = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $this->themeWeb . $this->uploadDir;

        //$url = $fullpath.$path;
        //$url = "img/" . $path;
        $url = $path;


        if (!($size = getimagesize($url)))
            return; // image doesn't exist

        if ($width == $size[0] && $height == $size[1]) {
            $webrootPath = FULL_BASE_URL . '/' . $webrootPath;
            $newPath = explode("$this->uploadDir", $path);
            $relfile = $webrootPath . $this->uploadDir . $newPath[1];
        } else {
            if ($autocrop) {
                $multiplier = 1.0;
                while (($width * $multiplier < $size[0]) && ($height * $multiplier < $size[1])) {
                    $multiplier += .01;
                }

                // make SURE we don't run over
                $multiplier -= .01;

                $cropw = floor($width * $multiplier);
                $croph = floor($height * $multiplier);

                //echo("$cropw $croph $width $height $multiplier $size[0] $size[1] <br />");
                ///die();

                $xindent = ($size[0] - $cropw) / 2.0;
                $yindent = ($size[1] - $croph) / 2.0;

                $startx = floor($xindent);
                $endx = $size[0] - ceil($xindent);

                $starty = floor($yindent);
                $endy = $size[1] - ceil($yindent);

                //echo("$xindent, $yindent -> leads to: $startx, $starty start and $endx, $endy end");
                //die();

                $cropvars = array($startx, $starty, $endx, $endy);
            }

            if (($width > $size[0] || $height > $size[1]) && $autocrop) {

                $multiplier = 1.0;
                while (($width * $multiplier >= $size[0]) || ($height * $multiplier >= $size[1])) {
                    $multiplier -= .01;
                }

                $cropw = floor($width * $multiplier);
                $croph = floor($height * $multiplier);

                //echo("$cropw $croph $width $height $multiplier $size[0] $size[1] <br />");
                ///die();

                $xindent = ($size[0] - $cropw) / 2.0;
                $yindent = ($size[1] - $croph) / 2.0;

                $startx = floor($xindent);
                $endx = $size[0] - ceil($xindent);

                $starty = floor($yindent);
                $endy = $size[1] - ceil($yindent);

                //echo("$xindent, $yindent -> leads to: $startx, $starty start and $endx, $endy end");
                //die();

                $cropvars = array($startx, $starty, $endx, $endy);
            }

            // check that user supplied full start and stop coordinates
            if (sizeof($cropvars) == 4) {
                if ($cropvars[0] > $size[0] || $cropvars[1] > $size[1] || $cropvars[2] > $size[0] || $cropvars[3] > $size[1] || $cropvars[0] < 0 || $cropvars[1] < 0 || $cropvars[2] < 0 || $cropvars[3] < 0) {
                    $crop = false;
                }
            } else {
                $crop = false;
            }

            // temporarily set size to this for aspect checking
            if ($crop) {
                $tempsize = array($size[0], $size[1]);
                $size[0] = $cropvars[2] - $cropvars[0];
                $size[1] = $cropvars[3] - $cropvars[1];
            }

            if ($aspect) { // adjust to aspect
                if (($size[1] / $height) > ($size[0] / $width))  // $size[0]:width, [1]:height, [2]:type
                    $width = ceil(($size[0] / $size[1]) * $height);
                else
                    $height = ceil($width / ($size[0] / $size[1]));
            }

            // set size back
            if ($crop) {
                $size[0] = $tempsize[0];
                $size[1] = $tempsize[1];
            }

            if ($crop) {
                $cropstring = $cropvars[0] . $cropvars[1] . $cropvars[2] . $cropvars[3] . '_';
            } else {
                $cropstring = '';
            }

            $webrootPath = FULL_BASE_URL . '/' . $webrootPath;
            $relfile = $webrootPath . $this->themeWeb . $this->uploadDir . $this->cacheDir . '/' . $width . 'x' . $height . '_' . $cropstring . basename($path); // relative file
            $cachefile = $fullpath . $this->cacheDir . DS . $width . 'x' . $height . '_' . $cropstring . basename($path);  // location on server


            if (file_exists($cachefile)) {
                $csize = getimagesize($cachefile);
                $cached = ($csize[0] == $width && $csize[1] == $height); // image is cached
                if (@filemtime($cachefile) < @filemtime($url)) // check if up to date
                    $cached = false;
            } else {
                $cached = false;
            }

            if (!$cached) {
                $resize = ($size[0] > $width || $size[1] > $height) || ($size[0] < $width || $size[1] < $height);
            } else {
                $resize = false;
            }

            if ($resize) {
                $image = call_user_func('imagecreatefrom' . $types[$size[2]], $url);

                if ($crop) {
                    if (function_exists("imagecreatetruecolor") && ($tempcrop = imagecreatetruecolor($cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1]))) {
                        imagecopyresampled($tempcrop, $image, 0, 0, $cropvars[0], $cropvars[1], $cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1], $cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1]);
                    } else {
                        $temp = imagecreate($cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1]);
                        imagecopyresized($tempcrop, $image, 0, 0, $cropvars[0], $cropvars[1], $cropvars[2] - $cropvars[0], $cropvars[3] - $cropvars[1], $size[0], $size[1]);
                    }

                    $image = $tempcrop;
                }

                $size[0] = $cropvars[2] - $cropvars[0];
                $size[1] = $cropvars[3] - $cropvars[1];

                if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor($width, $height))) {
                    imagecopyresampled($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                } else {
                    $temp = imagecreate($width, $height);
                    imagecopyresized($temp, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                }
                if ($types[$size[2]] == "jpeg") {
                    imagejpeg($temp, $cachefile, 90);
                } else {
                    call_user_func("image" . $types[$size[2]], $temp, $cachefile);
                }
                imagedestroy($image);
                imagedestroy($temp);
            }
            //return $this->output(sprintf($this->Html->image, $relfile, $this->Html->_parseAttributes($htmlAttributes, null, '', ' ')), $return);
        }
        if ($onlyPath) {
            return $relfile;
        }

        $htmlAttributes['src'] = $relfile;
        return $this->imageAttributes($htmlAttributes);
        //return '<img src="' . $relfile . '" alt="" />';
    }

    private function imageAttributes($htmlAttributes) {
        $imgAtr = array();

        if (!isset($htmlAttributes['src']) && empty($htmlAttributes['src']))
            return false;

        foreach ($htmlAttributes as $attribute => $value) {
            $imgAtr[] = "$attribute=" . '"' . $value . '"';
        }
        return "<img " . implode(' ', $imgAtr) . " />";
    }
    
    public function Fileupload($oldImageName = null, $imageArray, $imagePath, $rename = null) {
        //pr($imageArray);exit;

        if (is_uploaded_file($imageArray['tmp_name'])) {

            $filename = explode('.', $imageArray['name']);
            $ext = strtolower(end($filename));
            $imageArray['name'] = time() . ((!is_null($rename)) ? $rename . '.' . $ext : str_replace(" ", "-", $imageArray['name']));

            if ($ext === '.txt' || $ext === '.xlsx' || $ext === '.csv' || $ext === '.docx' || $ext === '.doc' || $ext === '.xls') {
                if (!is_dir($imagePath)) {
                    mkdir($imagePath, 0777);
                }
                if (!is_null($oldImageName)) {
                    $this->delete($oldImageName, $imagePath);
                }

                move_uploaded_file($imageArray['tmp_name'], $imagePath . '/' . $imageArray['name']);
            } else {
                echo 'Invalid image type. Upload only .txt,.xlsx,.xls,.docx,.doc,.csv image';
               // $this->Session->setFlash('Invalid image type. Upload only .png image', 'success');
              //  $this->Session->setFlash("Invalid image type. Upload only .png image");
                return false;
            }
        } else {
            echo 'Empty image file';
           // $this->Session->setFlash(__("Empty image file"));
            return false;
        }
        return $imageArray['name'];
    }

}

?>
