<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Employee;
use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AppointmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('service', EntityType::class, [
                'class' => Service::class,
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class
            ])
            ->add('start', DateTimeType::class, [
                'widget' => 'single_text'
            ])
            // ->add('end', DateTimeType::class, [
            //     'widget' => 'single_text'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
