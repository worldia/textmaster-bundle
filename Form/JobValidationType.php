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
        if ($options['accept']) {
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
            ;
        }

        $builder
            ->add('message', 'textarea', [
                'required' => !$options['accept'],
                'label' => 'job.validation.message.label',
            ])
            ->add('validate', 'submit', ['label' => 'job.validation.validate'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'textmaster');
        $resolver->setDefault('accept', true);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'textmaster_job_validation';
    }
}
