<?php
/**
 * Created by PhpStorm.
 * User: szilard
 * Date: 05.01.2018
 * Time: 10:37
 */

namespace Neogen\CommandSchedulerBundle\Form;

use Neogen\CommandSchedulerBundle\Entity\CommandEntity;
use Neogen\CommandSchedulerBundle\Service\CommandGetter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommandEntityType extends AbstractType
{
    /** @var CommandGetter */
    private $commandGetter;

    /**
     * CommandEntityType constructor.
     * @param CommandGetter $commandGetter
     */
    public function __construct(CommandGetter $commandGetter)
    {
        $this->commandGetter = $commandGetter;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $commands = $this->commandGetter->getCommands();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('command', ChoiceType::class, [
                'choices' => $commands,
                'label' => 'Command'
            ])
            ->add('arguments', TextType::class, [
                'label' => 'Arguments',
                'required' => false
            ])
            ->add('cronExpression', TextType::class, [
                'label' => 'Cron expression',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('priority', TextType::class, [
                'label' => 'Priority',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('executeNow', CheckboxType::class, [
                'label' => 'Execute now',
                'required' => false
            ])
            ->add('disabled', CheckboxType::class, [
                'label' => 'Disable',
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CommandEntity::class,
            'method' => 'POST',
            'csrf_protection' => false,
            'attr' => [
                'id' => 'commandEntityForm'
            ]
        ));
    }

    public function getBlockPrefix()
    {
        return;
    }
}