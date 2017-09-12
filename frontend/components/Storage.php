<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
//use Intervention\Image\ImageManager;

/**
 * File storage compoment
 *
 * @author admin
 */
class Storage extends Component implements StorageInterface
{

    private $fileName;

    /**
     * Save given UploadedFile instance to disk
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->preparePath($file);

        if ($path && $file->saveAs($path)) {
            return $this->fileName;
        }
    }

    /**
     * Prepare path to save uploaded file
     * @param UploadedFile $file
     * @return string|null
     */
    protected function preparePath(UploadedFile $file)
    {
        $this->fileName = $this->getFileName($file);
        //     0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg

        $path = $this->getStoragePath() . $this->fileName;
        //     /var/www/project/frontend/web/uploads/0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg

        $path = FileHelper::normalizePath($path);
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
    }

    public function deleteFile(string $filename){
        $file = $this->getStoragePath().$filename;
        //        if (file_exists('/var/www/project/frontend/web/uploads/75/18/ebcbfd1a59b263c2fb24c63bbb1956295207.jpg')){
        if (file_exists($file)){
            if (unlink($file)){
                //check empty or not directory
                if ($files = glob(substr($file,0,-41) . "/*")) {
                    return true;///var/www/project/frontend/web/uploads/75/18 not empty
                } else {
                    rmdir(substr($file,0,-41));//empty
                    rmdir(substr($file,0,-44));
                    return true;
                }
            }
        }
        return false;
    }

//    /**
//     * @param string $filename
//     * @return string
//     */
//    protected function deletePath(string $filename)
//    {
//        //  comes   0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
//        $path = $this->getStoragePath() . $filename;
//        //     /var/www/project/frontend/web/uploads/0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
//        return $path = FileHelper::normalizePath($path);
//    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilename(UploadedFile $file)
    {
        // $file->tempname   -   /tmp/qio93kf
            $hash = sha1_file($file->tempName);// 0ca9277f91e40054767f69afeb0426711ca0fddd


            $name = substr_replace($hash, '/', 2, 0);
            $name = substr_replace($name, '/', 5, 0);  // 0c/a9/277f91e40054767f69afeb0426711ca0fddd
            return $name . '.' . $file->extension;  // 0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
    }

    /**
     * @return string
     */
    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    /**
     *
     * @param string $filename
     * @return string
     */
    public function getFile(string $filename)
    {
        return Yii::$app->params['storageUri'].$filename;
    }
}