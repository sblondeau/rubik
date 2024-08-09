<?php

namespace App\Form;

use App\Entity\Ranking;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Emoji\EmojiTransliterator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RankingType extends AbstractType
{
  public function __construct()
  {
    $this->transliterator = EmojiTransliterator::create('text-emoji');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ## Simple CountryType
      ->add('country', CountryType::class, [  
      ])

      ##  CountryType = ChoiceType with list of countries in 'choices' eg ['FR' => 'France']
      # /!\ Need array_flip to reveerse key and value and obtain ['France' => 'FR']
      ->add('country', ChoiceType::class, [
        'choices' => array_flip(
          Countries::getNames()
        ),
      ])

      ## array_map to add ':isoCode:' behind each country name e.g 'France :fr:'
      ->add('country', ChoiceType::class, [
        'choices' => array_flip(
            array_map(
                function ($isoCode, $country) {
                    return
                    $country . ' ' .
                        ':' . strtolower($isoCode) . ':'
                    ;
                },
                array_keys(Countries::getNames()),
                Countries::getNames()
            )
        ),
      ])

      # Use EmojiTransliterator class to convert :text: to flag emoji 
        ->add('country', ChoiceType::class, [
          'choices' => array_flip(
              array_map(
                  function ($isoCode, $country) {
                      return
                      $country . ' ' .
                      $this->transliterator->transliterate(
                          ':' . strtolower($isoCode) . ':'
                      );
                  },
                  array_keys(Countries::getNames()),
                  Countries::getNames()
              )
          ),
        ])
    ;
  }

  
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Ranking::class,
    ]);
  }
}
