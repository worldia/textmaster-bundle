<?php

namespace Worldia\Bundle\TextmasterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Textmaster\Model\DocumentInterface;

class JobValidationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('satisfaction', 'choice', [
                'required' => true,
                'label' => 'job.validation.satisfaction.label',
                'data' => DocumentInterface::SATISFACTION_NEUTRAL,
                'choices' => [
                    DocumentInterface::SATISFACTION_NEGATIVE => 'job.validation.satisfaction.negative',
                    DocumentInterface::SATISFACTION_NEUTRAL => 'job.validation.satisfaction.neutral',
                    DocumentInterface::SATISFACTION_POSITIVE => 'job.validation.satisfaction.positive',
                ],
            ])
            ->add('message', 'textarea', [
                'required' => false,
                'label' => 'job.validation.message.label',
            ])
            ->add('accept', 'submit', ['label' => 'job.validation.accept'])
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
        return 'textmaster_job_validation';
    }
}
