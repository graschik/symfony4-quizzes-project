<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Данное имя уже используется")
 * @UniqueEntity(fields={"email"}, message="Данная почта уже используется")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     *
     * @Assert\Regex(
     *     pattern     = "/^[a-z]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Используйте латиницу"
     * )
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым. Удалите его если оно не нужно."
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 18,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)

     * @Assert\Length(
     *     min = 4,
     *     max = 18,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым. Удалите его если оно не нужно."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Пароль не может содержать русские символы"
     * )
     * @Assert\Length(
     *     min = 4,
     *     max = 18,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым. Удалите его если оно не нужно."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-zа-я]+$/iu",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Имя не должно содержать цифры"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым. Удалите его если оно не нужно."
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     * @Assert\Email(
     *      message = "Поле должно быть в формате электронной почты"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым. Удалите его если оно не нужно."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-zа-я]+$/iu",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Фамилия не должна содержать цифры"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(
     *     message = "Поле не должно быть пустым. Удалите его если оно не нужно."
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-zа-я]+$/iu",
     *     htmlPattern = "^[a-zA-Z]+$",
     *     message="Отвечство не должно содержать цифры"
     * )
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Число символов не должно быть меньше {{ limit }}",
     *     maxMessage = "Число символов не должно быть больше {{ limit }}"
     * )
     */
    private $secondname;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    private $recoveryToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="user", orphanRemoval=true)
     */
    private $games;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->setUsername('');
        $this->setPassword('');
        $this->setFirstname('');
        $this->setEmail('');
        $this->setSecondname('');
        $this->setSurname('');
        $this->games = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return User|static
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return void
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isActive;
    }

    /** @see \Serializable::serialize()
     * @return string
     */
    public function serialize(): string
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname = null): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname = null): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getSecondname(): ?string
    {
        return $this->secondname;
    }

    public function setSecondname(string $secondName = null): self
    {
        $this->secondname = $secondName;

        return $this;
    }

    public function setUsername(string $username = null): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password = null): self
    {
        $this->password = $password;

        return $this;
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
    public function setPlainPassword($plainPassword = null)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return User
     */
    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param mixed $isActive
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecoveryToken(): string
    {
        return $this->recoveryToken;
    }

    /**
     * @param mixed $recoveryToken
     * @return User
     */
    public function setRecoveryToken(string $recoveryToken): self
    {
        $this->recoveryToken = $recoveryToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    /**
     * @param Game $game
     * @return User
     */
    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setUser($this);
        }

        return $this;
    }

    /**
     * @param Game $game
     * @return User
     */
    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getUser() === $this) {
                $game->setUser(null);
            }
        }

        return $this;
    }


}