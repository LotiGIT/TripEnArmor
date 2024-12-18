<?php

class ImageController
{
    private $uploadDir;
    public function __construct()
    {
        $this->uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/html/public/images/';
    }

    public function getImagesOfOffre($id_offre)
    {
        $result = [
            "carte" => false,
            "plan" => false,
            "details" => false
        ];
        $allImages = scandir($this->uploadDir);

        foreach ($allImages as $image) {
            $name = explode(".", $image)[0];
            $subparts = explode("-", $name);

            if ($subparts[0] == $id_offre) {
                if ($subparts[1] == "carte") {
                    $result["carte"] = $image;
                } else if ($subparts[1] == "plan") {
                    $result["plan"] = $image;
                } else {
                    if ($result["details"] === false) {
                        $result["details"] = [];
                    }
                    $result["details"][] = $image;
                }
            }
        }

        return $result;
    }

    public function uploadImage($id_offre, $champ, $actual_path, $extension)
    {
        return move_uploaded_file($actual_path, $this->uploadDir . $id_offre . "_" . $champ . '.' . $extension);
    }
}