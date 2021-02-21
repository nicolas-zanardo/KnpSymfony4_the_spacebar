<?php


namespace App\Form\Model;


use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="please enter an email")
     * @Assert\Email()
     * @UniqueUser()
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Choose a password")
     * @Assert\Length(
     *     min=5,
     *     minMessage="Come on, you can think of a password longer than that!"
     *     )
     */
    private $plainPassword;

    /**
     * @Assert\IsTrue(message="I know, it's silly, but you must agree to our terms")
     */
    private $agreeTerms;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAgreeTerms()
    {
        return $this->agreeTerms;
    }

    /**
     * @param mixed $agreeTerms
     */
    public function setAgreeTerms($agreeTerms): void
    {
        $this->agreeTerms = $agreeTerms;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}