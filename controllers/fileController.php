<?
defined('_VALID') or die();

class fileController extends controller
{
    var $templates = Array(
        'upload' => Array('empty', 2)
    );

    function upload()
    {
        $entity = getParam($_REQUEST, "entity");
        if (!$entity)
            return;

        $filename = ABS_PATH . "/uploads/" . $entity;

        if (!file_exists($filename)) {
               mkdir($filename, 777);
        } 

        foreach ($_FILES as $file) {
            $newName = generateFileName($file["name"][0]);
            move_uploaded_file($file["tmp_name"][0], $filename . "/" . $newName);

            header('Content-Type: application/json');
            echo json_encode(Array($newName));            
        }
    }
}