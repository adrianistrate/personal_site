<?php

namespace App\EventListener;

use App\Entity\Image;
use App\Service\ImageManipulation;

/**
 * Class BaseImageListener
 * @package App\EventListener
 */
class BaseImageListener
{
    /**
     * @var ImageManipulation
     */
    private $imageManipulation;

    /**
     * BaseImageListener constructor.
     * @param ImageManipulation $imageManipulation
     */
    public function __construct(ImageManipulation $imageManipulation)
    {
        $this->imageManipulation = $imageManipulation;
    }

    /**
     * @param Image $image
     * @throws \ImagickException
     */
    public function postUpdate(Image $image)
    {
        $this->imageManipulation->removeOldFile($image);
        $this->imageManipulation->addFrame($image);
    }

    /**
     * @param Image $image
     * @throws \ImagickException
     */
    public function postPersist(Image $image)
    {
        $this->imageManipulation->addFrame($image);
    }

    /**
     * @param Image $image
     */
    public function prePersist(Image $image)
    {
        $this->imageManipulation->upload($image);
    }

    /**
     * @param Image $image
     */
    public function preUpdate(Image $image)
    {
        if($image->getFile()) {
            $image->prepareOldFile();
        }
        $this->imageManipulation->upload($image);
    }

    public function postRemove(Image $image)
    {
        $this->imageManipulation->removeFile($image);
    }
}