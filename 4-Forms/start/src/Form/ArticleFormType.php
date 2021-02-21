<?php


namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(UserRepository $userRepository, SessionInterface $session)
    {
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();
        $location = $article ? $article->getLocation() : null;

        $builder
            ->add('title', Type\TextType::class, [
                'help' => 'Choose something catchy!'

            ])
//            ->add('content', Type\TextareaType::class, [
//                'attr' => ['rows' => 50]
//            ])
            ->add('content', null, [
                'rows' => 15
            ])
//            ->add('publishedAt', null, [
//                "widget" => 'single_text'
//            ])
//            ->add('author', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => function (User $user) {
//                    return sprintf('%s - %s (%d)', $user->getEmail(),
//                        strtoupper($user->getFirstName()), $user->getId());
//                },
//                'placeholder' => 'Choose an author',
//                'choices' => $this->userRepository
//                    ->findAllEmailAlphabetical(),
//                'preferred_choices' => [
//                    $this->userRepository->findOneBy([
//                        "email" => $this->session->get('_security.last_username')
//                    ])
//                ],
//                'invalid_message' => 'Symfony is too smart for your hacking!'
//            ])
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit
            ])
            ->add('location', Type\ChoiceType::class, [
                'placeholder' => 'Choose a location',
                'choices' => [
                    'The Solar System' => "solar_system",
                    'Near a start' => "star",
                    'Interstellar Space' => "interstellar_space"
                ],
                'required' => false
            ])
            ;


        if ($options['include_published_at']) {
            $builder->add('publishedAt', null, [
                "widget" => 'single_text'
            ]);
        }

        if ($location) {
            $builder->add('specificLocationName', Type\ChoiceType::class, [
                'placeholder' => 'Where exactly',
                'choices' => $this->getLocationNameChoices($location),
                'required' => false
            ]);
        }

        $builder->get('location')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->setupSpecificLocationNameField(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {

                /** @var Article|null $data */
                $data = $event->getData();

                if (!$data) {
                    return;
                }

                $this->setupSpecificLocationNameField(
                    $event->getForm(),
                    $data->getLocation()
                );
            }
        );
    }

    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];
        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];
        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];
        return $locationNameChoices[$location] ?? null;
    }

    private function setupSpecificLocationNameField(FormInterface $form, ?string $location) {
        if (null === $location) {
            $form->remove('specificLocationName');
            return;
        }

        $choices = $this->getLocationNameChoices($location);

        if (null === $choices) {
            $form->remove('specificLocationName');
            return;
        }

        $form->add('specificLocationName', Type\ChoiceType::class, [
            'placeholder' => 'Where exactly?',
            'choices' => $choices,
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false
        ]);
    }
}