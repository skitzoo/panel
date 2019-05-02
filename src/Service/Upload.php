<?php
namespace App\Service;

class Upload
{
    public function __construct()
    {
    }

    public function Send($file, $directory)
    {
        if($file == null)
            return null;

        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        $file->move($directory,$fileName);
        return $fileName;
    }


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}