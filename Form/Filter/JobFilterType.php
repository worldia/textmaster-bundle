<?php

namespace Worldia\Bundle\TextmasterBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
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
            ->add('status', 'choice', [
                'required' => false,
                'label' => 'job.status.title',
                'empty_value' => 'job.status.all',
                'choices' => [
                    JobInterface::STATUS_CREATED => 'job.status.created',
                    JobInterface::STATUS_STARTED => 'job.status.started',
                    JobInterface::STATUS_FINISHED => 'job.status.finished',
                    JobInterface::STATUS_VALIDATED => 'job.status.validated',
                ],
            ])
            ->add('filter', 'submit', ['label' => 'job.filter'])
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'textmaster_job_filter';
    }
}
