<?php


namespace App\Form\DataTransformer;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class EmailToUserTransformer implements DataTransformerInterface
{

    private $userRepository;
    private $finderCallable;

    public function __construct(UserRepository $userRepository, callable $finderCallable)
    {
        $this->userRepository = $userRepository;
        $this->finderCallable = $finderCallable;
    }

    public function transform($value): ?string
    {
        if(null === $value) {
            return "";
        }

        if(!$value instanceof User) {
            throw new \LogicException(
                "The UserSelectTextType can only be used with User Object!"
            );
        }

        return $value->getEmail();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

//        $user = $this->userRepository->findOneBy(['email' => $value]);

        $callback = $this->finderCallable;
        $user = $callback($this->userRepository, $value);

        if (!$user) {
            throw new TransformationFailedException(sprintf(
                'No user found with email "%s" ', $value
            ));
        }

        return $user;
    }
}