<?php

namespace Anax\Mymodule;

/**
 * Class for checking and processing uploaded gif, png and jpg images
 *
 */
class UploadController implements \Anax\DI\IInjectionAware {
    use \Anax\DI\TInjectable;
    
    private $upload;    
    private $imgpath;
    private $ext;
    private $filename;

    /**
     * Constructor
     *
     */
    public function __construct() {

        
    }
    
    /**
 * Initialize the controller.
 *
 * @return void
 */
    public function initialize() {
    
        $this->upload = new \Anax\Mymodule\Upload();
        $this->upload->setDI($this->di);
    }
            
    
    public function entryAction() {
        $this->initialize();            
        $res = $this->checkUpload();
        if($res === true){
            $this->process(); 
        } else {
           $this->theme->setVariable('wrapperclass', 'typography'); 
           $this->views->add('mymodule/messages', [
           'message' => $res,
        ]);                    
        }
    }
    
    private function checkUpload(){

        try {
   
            if (
                !isset($_FILES['img']['error']) ||
                is_array($_FILES['img']['error'])
                ) {
            throw new \Anax\Exception('Invalid parameters.');
            }

            switch ($_FILES['img']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \Anax\Exception('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \Anax\Exception('Exceeded filesize limit defined in form.');
            default:
                throw new \Anax\Exception('Unknown errors.');
        }
            
            if(mb_strlen($_FILES['img']['name'],"UTF-8") > 100) {
            throw new \Anax\Exception('Filename is too long.');
        }
            $this->filename = $_FILES['img']['name'];
        
            if($_FILES['img']['size'] > 2000000) {
            throw new \Anax\Exception('Exceeded filesize limit defined in script.');
        }        

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (false === $this->ext = array_search(
                $finfo->file($_FILES['img']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    ),
                true
        )) {
        throw new \Anax\Exception('Invalid file format. Please make sure to only upload GIF, PNG or JPG images.');
        }
            $this->imgpath = sprintf('img/upload/%s.%s',
            sha1_file($_FILES['img']['tmp_name']), $this->ext);
            if(!move_uploaded_file(
                $_FILES['img']['tmp_name'],
                $this->imgpath
            )
        ) {
        throw new \Anax\Exception('Failed to move uploaded file. Check to see that you have a folder named \'img/upload\' in your Anax webroot folder');
        }    
          
            
            return true; // passed all checks

        } catch (\Anax\Exception $e) {

            $message = $e->getMessage();
            return $message;

        }
    }


    private function process() {
        $this->initialize();            

        $size = 300; // the default image height
        chmod ($this->imgpath, octdec('0666')); // read-write
        $sizes = getimagesize($this->imgpath);
        $aspect_ratio = $sizes[1]/$sizes[0]; //kollar förh mellan höjd o bredd
        if ($sizes[1] <= $size) {
            $new_width = $sizes[0];
            $new_height = $sizes[1];
        } else {
            $new_height = $size; //om bilden är för stor använder vi en given höjd
            $new_width = abs($new_height/$aspect_ratio); //bredden räknas ut mha av förh
        }
        
        $destimg=imagecreatetruecolor($new_width,$new_height)
            or die('Det gick inte att skapa bilden'); // skapar en ny 'svart' bild           

        /*sedan skapar vi en ny bild med bildtypen jpg, gif eller png*/

        switch ($this->ext) {
            case 'gif' :
                $srcimg = imagecreatefromgif($this->imgpath)
                or die('Källbilden gick inte att öppna');
                break;
            case 'jpg' :
                $srcimg = imagecreatefromjpeg($this->imgpath)
                or die('Källbilden gick inte att öppna');
                break;
            case 'png' :
                $srcimg = imagecreatefrompng($this->imgpath)
                or die('Källbilden gick inte att öppna');
                break;
        }

        /* Är det en png- eller gif bild så ser vi till att behålla transparensen */            
        if($this->ext == 'png' || $this->ext == 'gif'){
            imagecolortransparent($destimg, imagecolorallocatealpha($destimg, 0, 0, 0, 0)); // numbers are red, green, blue and last alpha
            imagealphablending($destimg, false);
            imagesavealpha($destimg, true); //true betyder att all alphakanal-info sparas
        }
        /*kopierar rektangel från bilden i $srcimg till bilden i $destimg och komprimerar samtidigt bilden*/	
        imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg))
        or die('Det gick inte att skapa en ny storlek');
            

        /*här gör vi om bilden till en png-, gif- eller jpg-bild*/
        switch ($this->ext) {
        case 'gif' :
            imagegif($destimg,$this->imgpath) //spara som gif-fil och skriv över den ursprungliga filen som skapades av move_uploaded_file
            or die('Det gick inte att spara gif-bilden');           
            break;
        case 'jpg' :
            imagejpeg($destimg,$this->imgpath) 
            or die('Det gick inte att spara jpg-bilden');
            break;
        case 'png' :
            imagepng($destimg,$this->imgpath)
            or die('Det gick inte att spara png-bilden');
            break;
        }
        
        $filename = preg_replace(array('/å/', '/ä/', '/ö/', '/Å/', '/Ä/', '/Ö/', '/[^a-zA-Z0-9\/_|+ .-]/', '/[ -]+/', '/^-|-$/'),
        array('a', 'a', 'o', 'a', 'a', 'o', '', '_', ''), strtolower($this->filename));
        
 
        $this->upload->add([
            'san_filename' => $filename,
            'path'         => $this->imgpath, 
        ]);
        
        $res = $this->upload->findLast();
        $lastimage = $this->upload->getProperties($res);
        $url = $this->di->url->asset($this->imgpath);
        $this->theme->setVariable('wrapperclass', 'typography'); 
        
        $this->views->add('mymodule/uploadform', [
        ]); 
        
        $img_cap = "<img src=$url alt='Uploaded image' /><br>
        <p>Sanitized original filename stored in database, can e.g. be used to represent this image in a download list:<strong> ".$lastimage['san_filename']."</strong></p>
        <p>Relative image path on disk with secure filename, stored in database: <strong>".$lastimage['path']."</strong></p>";
        
        $this->views->addString($img_cap, 'main');

        
 
        
  
    }
}
        
