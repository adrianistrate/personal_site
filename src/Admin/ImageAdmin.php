<?php

namespace App\Admin;

use App\Entity\Image;
use App\Service\ImageManipulation;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class ImageAdmin
 * @package App\Admin
 */
final class ImageAdmin extends AbstractAdmin
{
    /**
     * @var ImageManipulation|null
     */
    private $imageManipulation = null;

    /**
     * @param ImageManipulation $imageManipulation
     */
    public function setImageManipulation(ImageManipulation $imageManipulation)
    {
        $this->imageManipulation = $imageManipulation;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // get the current Image instance
        /** @var Image $image */
        $image = $this->getSubject();

        // use $fileFieldOptions so we can add other options to the field
        $fileFieldOptions = ['required' => false];
        if ($image && ($webPath = $this->imageManipulation->getWebPath($image))) {
            // add a 'help' option containing the preview's img tag
            $fileFieldOptions['sonata_help'] = '<img src="' . $webPath . '" class="admin-preview"/>';
        }

        $formMapper
            ->add('file', FileType::class, $fileFieldOptions)
            ->add('frame', ChoiceType::class, [
                'choices' => array_flip($this->imageManipulation->getFrames()),
                'placeholder' => "None"
            ])
            ->add('is_main');
    }
}