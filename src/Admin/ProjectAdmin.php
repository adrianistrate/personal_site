<?php

namespace App\Admin;

use App\Service\ImageManipulation;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

final class ProjectAdmin extends AbstractAdmin
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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content', ['class' => 'col-md-9'])
            ->add('name')
            ->add('subtitle')
            ->add('type')
            ->end()
            ->with('Settings', ['class' => 'col-md-3'])
            ->add('allow_see_more')
            ->add('enabled')
            ->add('bg_color', ColorType::class)
            ->end()
            ->with('Dates', ['class' => 'col-md-3'])
            ->add('started_on', DatePickerType::class)
            ->add('ended_on', DatePickerType::class)
            ->end()
            ->with('Images')
            ->add('images', CollectionType::class, [
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'admin_code' => 'admin.image',
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('subtitle')
            ->add('enabled');
    }
}