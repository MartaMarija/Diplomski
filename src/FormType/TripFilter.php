<?php

namespace App\FormType;

use App\Repository\SectionRepository;
use Pimcore\Model\DataObject\HikingAssociation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripFilter extends AbstractType
{
    public function __construct(
        private SectionRepository $sectionRepository,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var HikingAssociation $hikingAssociation */
        $hikingAssociation = $options['hikingAssociation'];

        $sections = $this->sectionRepository->getSectionsByHikingAssociation($hikingAssociation);

        $choices = [];
        foreach ($sections as $section) {
            $choices[$section->getName()] = $section->getId();
        }

        $builder
            ->add('Name', TextType::class, [
                'label' => 'NAZIV IZLETA',
                'attr' => [
                    'placeholder' => 'Naziv...',
                    'class' => 'input',
                ],
            ])
            ->add('Section', ChoiceType::class, [
                'label' => 'SEKCIJA',
                'placeholder' => '-- Odaberite sekciju --',
                'choices' => $choices
            ])
            ->add('Type', ChoiceType::class, [
                'label' => 'VRSTA IZLETA',
                'placeholder' => '-- Odaberite vrstu izleta --',
                'choices' => [
                    'Završeni' => 'finished',
                    'Nadolazeći' => 'incoming'
                ]
            ])
            ->add('StartDate', DateType::class, [
                'label' => 'DATUM POLASKA',
                'widget' => 'single_text'
            ])
            ->add('StartDateSort', ChoiceType::class, [
                'label' => 'SORTIRAJ',
                'choices' => [
                    'Datum polaska - najnoviji' => 'DESC',
                    'Datum polaska - najstariji' => 'ASC'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'hikingAssociation' => null,
        ]);
    }
}
