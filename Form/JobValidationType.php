<?php

namespace Worldia\Bundle\TextmasterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                ->add('satisfaction', ChoiceType::class, [
                    'required' => true,
                    'label' => 'job.validation.satisfaction.label',
                    'data' => DocumentInterface::SATISFACTION_NEUTRAL,
                    'choices' => [
                        'job.validation.satisfaction.negative' => DocumentInterface::SATISFACTION_NEGATIVE,
                        'job.validation.satisfaction.neutral' => DocumentInterface::SATISFACTION_NEUTRAL,
                        'job.validation.satisfaction.positive' => DocumentInterface::SATISFACTION_POSITIVE,
                    ],
                ])
            ;
        }

        $builder
            ->add('message', TextareaType::class, [
                'required' => !$options['accept'],
                'label' => 'job.validation.message.label',
            ])
            ->add('validate', SubmitType::class, ['label' => 'job.validation.validate'])
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
}
