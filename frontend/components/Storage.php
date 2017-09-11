<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use Intervention\Image\ImageManager;

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
        echo '<pre>';
        echo '<br>';
        print_r($filename);
        echo '<br>';
        print_r($this->deletePath($filename));
        echo '<br>';
//        print_r($user->picture);
//        echo '<br>';
//        print_r(Yii::$app->storage->getFile($user->picture));
        echo '<pre>';
//        if (file_exists('/var/www/project/frontend/web/uploads/75/18/ebcbfd1a59b263c2fb24c63bbb1956295207.jpg')){
        if (file_exists($this->deletePath($filename))){
            if (unlink($this->deletePath($filename))){
                return true;
            }

        }
        return false;
    }

    protected function deletePath(string $filename)
    {
        //  comes   0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg

        $path = $this->getStoragePath() . $filename;
        //     /var/www/project/frontend/web/uploads/0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg

       return $path = FileHelper::normalizePath($path);

//        if (FileHelper::createDirectory(dirname($path))) {
//            return $path;
//        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilename(UploadedFile $file)
    {
        // $file->tempname   -   /tmp/qio93kf
        if ($this->fileResize($file)) {
            $hash = sha1_file($file->tempName);// 0ca9277f91e40054767f69afeb0426711ca0fddd


            $name = substr_replace($hash, '/', 2, 0);
            $name = substr_replace($name, '/', 5, 0);  // 0c/a9/277f91e40054767f69afeb0426711ca0fddd
            return $name . '.' . $file->extension;  // 0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
        }
        return null;
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    protected function fileResize(UploadedFile $file)
    {
        $manager = new ImageManager(array('driver' => 'imagick'));
        $tempFileForResize = $manager->make($file->tempName);
        $widthOrigin = $tempFileForResize->getWidth($file->tempName);
        $heightOrigin = $tempFileForResize->getHeight($file->tempName);
        $widthAvatarImageParams = Yii::$app->params['widthImageAvatar'];
        $heightAvatarImageParams = Yii::$app->params['heightImageAvatar'];
        $widthNewSize = 0;
        $heightNewSize = 0;
        $changed = false;

        if ($widthOrigin > $widthAvatarImageParams) {
            $widthNewSize = $widthAvatarImageParams;
            $heightNewSize = $heightOrigin;
            $changed = true;
        }
        if ($heightOrigin > $heightAvatarImageParams) {
            $heightNewSize = $heightAvatarImageParams;
            ($widthNewSize = 0) ? $widthNewSize = $widthOrigin : $widthNewSize = $widthAvatarImageParams;
                $changed = true;
        }

        if ($changed) {
            return $tempFileForResize->resize($widthNewSize, $heightNewSize)->save();
        } else {
            return true;
        }
        return false;
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