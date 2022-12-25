<?php

namespace App\Controllers;

use App\Models\User;

class Home extends BaseController
{
    protected $user;

    //Метод __construct() - это конструктор класса, этот метод вызывается 1 раз при обращении к классу (при создании объекта класса)
    public function __construct()
    {
        //Заполняем companys объектом таблицы
    }

    public function index()
    {
        return view('dashboard');
    }

    public function upload()
    {
        $validationRule = [
            'userfile' => [
                'label' => 'Image File',
                'rules' => 'uploaded[source]'
                    . '|is_image[source]'
                    . '|mime_in[source,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
//                    . '|max_size[source,10000]'
//                    . '|max_dims[source,1024,768]',
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];
            return view('dashboard', $data);
        }


        $img = $this->request->getFile('source');

        if (!$img->hasMoved()) {
            $filepath = "uploads/" . $img->store();
            $data = ["uploaded" => "/" . $filepath];
            return view('dashboard', $data);
        }
        $data = ['errors' => 'The file has already been moved.'];
        return view('dashboard', $data);


    }

    public function recognize()
    {

        $x = $this->request->getPost("x");
        $y = $this->request->getPost("y");
        $h = $this->request->getPost("h");
        $w = $this->request->getPost("w");
        $image = substr($this->request->getPost("image"), 1);


        $image_data = getimagesize($image);
        $media_type = $image_data['mime'];
        switch ($media_type) {                                           // If media type is
            case 'image/gif' :                                          // GIF
                $orig = imagecreatefromgif($image);                 // Open GIF
                break;                                                  // End of switch
            case 'image/jpeg' :                                         // JPEG
                $orig = imagecreatefromjpeg($image);                // Open JPEG
                break;                                                  // End of switch
            case 'image/png' :                                          // PNG
                $orig = imagecreatefrompng($image);                 // Open PNG
                break;                                                  // End of switch
        }

        $new = imagecreatetruecolor($w, $h);           // New blank image
        imagecopyresampled($new, $orig, 0, 0, $x, $y, $w, $h, $w, $h); // Crop and resize

        $newfilename = time() . '_' . bin2hex(random_bytes(10));


        switch ($media_type) {                                           // If media type is
            case 'image/gif' :                                          // GIF
                $part = "uploads/parts/" . $newfilename . ".gif";
                imagegif($new, $part);                      // Save GIF
                break;                                                    // End of switch
            case 'image/jpeg':                                          // JPEG
                $part = "uploads/parts/" . $newfilename . ".jpg";
                imagejpeg($new, $part);                     // Open JPEG
                break;                                                    // End of switch
            case 'image/png' :
                $part = "uploads/parts/" . $newfilename . ".png";
                //die($part);
                imagepng($new, $part);                      // Open PNG
                break;                                                    // End of switch
        }

        $way = "/var/www/html/public/".$part;
        $cmd = "/usr/bin/tesseract $way stdout -l rus";

        exec($cmd, $msg);
        $msg = implode(' ', $msg);

        unlink($part);

        echo $msg;

    }

}
