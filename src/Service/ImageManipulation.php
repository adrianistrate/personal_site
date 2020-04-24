<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Image\Box;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class ImageManipulation
 * @package App\Service
 */
class ImageManipulation
{
    /**
     *
     */
    const FRAME_NONE = 0;
    /**
     *
     */
    const FRAME_BROWSER = 1;
    /**
     *
     */
    const FRAME_PHONE = 2;
    /**
     *
     */
    const FRAME_TABLET = 3;

    /**
     * @var Container
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var string
     */
    private $imagesUploadDir;
    /**
     * @var string
     */
    private $imagesUploadPath;
    /**
     * @var string
     */
    private $framesDir;

    /**
     * ImageManipulation constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @param string $imagesUploadDir
     * @param string $imagesUploadPath
     * @param string $framesDir
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, string $imagesUploadDir, string $imagesUploadPath, string $framesDir)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->imagesUploadDir = $imagesUploadDir;
        $this->imagesUploadPath = $imagesUploadPath;
        $this->framesDir = $framesDir;
    }

    /**
     * @param Image $image
     * @throws \ImagickException
     */
    public function addFrame(Image $image)
    {
        $frameNames = self::getFrames();

        if($image->getFrame() == self::FRAME_NONE) {
            $this->removeAllImagesGeneratedWithFrames($image->getFilename());
            return;
        }

        $imageImagick = new \Imagick($this->getAbsolutePath($image));
        $imageObject = new \Imagine\Imagick\Image($imageImagick, new RGB(), new MetadataBag());

        $frameBrowserPath = $this->framesDir . DIRECTORY_SEPARATOR . $this->getFramePngName($image);

        $imagick = new \Imagick($frameBrowserPath);
        $frameObject = new \Imagine\Imagick\Image($imagick, new RGB(), new MetadataBag());

        $startPoint = $this->getStartPointForFrame($image);
        $resizeBox = $this->getResizeBoxForFrame($image);

        $imageObject = $imageObject->resize(new Box($resizeBox[0], $resizeBox[1]));

        $frameObject->paste($imageObject, new Point($startPoint[0], $startPoint[1]));

        $zoom = $this->zoomOutputForFrame($image);
        $frameObjectSizes = $frameObject->getSize();
        $width = $frameObjectSizes->getWidth();
        $height = $frameObjectSizes->getHeight();

        $frameObject = $frameObject->resize(new Box($zoom * $width, $zoom * $height));

        $frameObject->save(dirname($this->getAbsolutePath($image)) . DIRECTORY_SEPARATOR . strtolower($frameNames[$image->getFrame()]) . '-' . $image->getFilename());
    }

    /**
     * @param Image $image
     */
    public function upload(Image $image)
    {
        if (null === $image->getFile()) {
            return;
        }

        $destinationFilename = md5(time()) . '_' . $image->getFile()->getClientOriginalName();

        $image->getFile()->move($this->imagesUploadDir, $destinationFilename);

        $image->setFilename($destinationFilename);

        // clean up the file property as you won't need it anymore
        $image->setFile(null);
    }

    /**
     * @param Image $image
     * @param string $prefix
     * @return string|null
     */
    public function getAbsolutePath(Image $image, $prefix = '')
    {
        return null === $image->getFilename() ? null : $this->imagesUploadDir . '/' . $prefix . $image->getFilename();
    }

    /**
     * @param Image $image
     * @return string|null
     */
    public function getOldAbsolutePath(Image $image)
    {
        return null === $image->getOldFilename() ? null : $this->imagesUploadDir . '/' . $image->getOldFilename();
    }

    /**
     * @param Image $image
     * @param string $prefix
     * @return string|null
     */
    public function getWebPath(Image $image, $prefix = '')
    {
        return null === $image->getFilename() ? null : $this->imagesUploadPath . $prefix . $image->getFilename();
    }

    /**
     * @param bool $includeNone
     * @return array
     */
    public static function getFrames($includeNone = false)
    {
        $frames = array(
            self::FRAME_BROWSER => 'Browser',
            self::FRAME_PHONE => 'Phone',
            self::FRAME_TABLET => 'Tablet'
        );

        if ($includeNone) {
            $frames[self::FRAME_NONE] = 'None';
        }

        return $frames;
    }

    /**
     * @param Image $image
     * @return string
     */
    private function getFramePngName(Image $image)
    {
        switch ($image->getFrame()) {
            case self::FRAME_BROWSER: {
                return 'mac.png';
            }
            case self::FRAME_PHONE: {
                return 'iphone.png';
            }
            case self::FRAME_TABLET: {
                return 'ipad.png';
            }
        }
    }

    /**
     * @param Image $image
     * @return array
     */
    private function getStartPointForFrame(Image $image)
    {
        switch ($image->getFrame()) {
            case self::FRAME_BROWSER: {
                return array(322, 70);
            }
            case self::FRAME_TABLET: {
                return array(223, 212);
            }
            case self::FRAME_PHONE: {
                return array(76, 240);
            }
        }
    }

    /**
     * @param Image $image
     * @return array
     */
    private function getResizeBoxForFrame(Image $image)
    {
        switch ($image->getFrame()) {
            case self::FRAME_BROWSER: {
                return array(1440, 900);
            }
            case self::FRAME_TABLET: {
                return array(2050, 1538);
            }
            case self::FRAME_PHONE: {
                return array(648, 1134);
            }
        }
    }

    /**
     * @param Image $image
     * @return float|int
     */
    private function zoomOutputForFrame(Image $image)
    {
        switch ($image->getFrame()) {
            case self::FRAME_BROWSER: {
                return 1;
            }
            case self::FRAME_TABLET: {
                return 0.35;
            }
            case self::FRAME_PHONE: {
                return 0.42;
            }
        }
    }

    /**
     * @param Image $image
     * @return string|null
     */
    public function getWebPathWithFrame(Image $image)
    {
        if ($image->getFrame() == self::FRAME_NONE) {
            return $this->getWebPath($image);
        } else {
            $frameNames = self::getFrames();

            return $this->imagesUploadPath . strtolower($frameNames[$image->getFrame()]) . '-' . $image->getFilename();
        }
    }

    /**
     * @param Image $image
     */
    public function removeFile(Image $image)
    {
        if ($file = $this->getAbsolutePath($image)) {
            @unlink($file);

            $this->removeAllImagesGeneratedWithFrames($image->getFilename());
        }
    }

    /**
     * @param Image $image
     */
    public function removeOldFile(Image $image)
    {
        if (($file = $this->getOldAbsolutePath($image))) {
            unlink($file);

            $this->removeAllImagesGeneratedWithFrames($file);
        }
    }

    private function removeAllImagesGeneratedWithFrames($imageFilename)
    {
        $frameNames = self::getFrames();

        foreach($frameNames as $frameName) {
            $frameImagePath = $this->imagesUploadDir . DIRECTORY_SEPARATOR . strtolower($frameName) . '-' . $imageFilename;
            if(file_exists($frameImagePath)) {
                unlink($frameImagePath);
            }
        }
    }
}