<?php

namespace Worldia\Bundle\TextmasterBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;

class JobFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'required' => false,
                'label' => 'job.status.title',
                'placeholder' => 'job.status.all',
                'empty_data' => null,
                'choices' => [
                    'job.status.created' => JobInterface::STATUS_CREATED,
                    'job.status.started' => JobInterface::STATUS_STARTED,
                    'job.status.finished' => JobInterface::STATUS_FINISHED,
                    'job.status.validated' => JobInterface::STATUS_VALIDATED,
                ],
            ])
            ->add('filter', SubmitType::class, ['label' => 'job.filter'])
            ->setMethod('GET')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'textmaster');
    }
}
