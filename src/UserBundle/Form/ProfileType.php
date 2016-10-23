<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType;

class ProfileType extends AbstractType
{
    // public function buildForm(FormBuilderInterface $builder, array $options)
    // {
    //     $builder->add('timeZone');
    // }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'user_profile';
    }

    // public function getTimeZone()
    // {
    //     return $this->getBlockPrefix();
    // }
}