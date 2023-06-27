<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Employee;
use App\Entity\Appointment;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
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
                'placeholder' => 'Choisir une prestation',
            ]);
            // ->add('start', DateTimeType::class, [
            //     'widget' => 'single_text',
            //     'required' => true,
            // ]);

        $formModifier = function (FormInterface $form, Service $service = null) {
            $employees = null === $service ? [] : $service->getEmployee();

            $form->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choices' => $employees,
                'placeholder' => 'Choisir un coiffeur / une coiffeuse',
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $service = $data->getService();

                if ($service instanceof Service || $service === null) {
                    $formModifier($event->getForm(), $service);
                }
            }
        );

        $builder->get('service')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $service = $event->getForm()->getData();

                if ($service instanceof Service || $service === null) {
                    $formModifier($event->getForm()->getParent(), $service);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
