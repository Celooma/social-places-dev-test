<?php

namespace App\Entity;

use App\Enums\ContactGender;
use App\Repository\ContactRepository;
use App\Traits\Entity\HasDateCreated;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use HasDateCreated;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Store::class, cascade: ['persist'], fetch: 'LAZY')]
    #[ORM\JoinColumn('store_id', 'id')]
    private Store $store;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\NotBlank]
    private string $surname;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private string $email;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank]
    private string $cellphone;

    #[ORM\Column(type: 'integer', nullable: true,length:10, enumType: ContactGender::class)]
    private ContactGender $gender;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;

    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;

    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCellphone(): ?string
    {
        return $this->cellphone;
    }

    public function setCellphone(string $cellphone): void
    {
        $this->cellphone = $cellphone;
    }

    public function getGender(): ContactGender
    {
        return $this->gender;
    }

    public function setGender(ContactGender $gender): void
    {
        $this->gender = $gender ;
    }

    public function setGenderFromName(string $genderName): void {
        $this->gender = ContactGender::fromName($genderName);
    }
    public function setStore(Store $store): void{
        $this->store = $store;
    }

    public function getStore(): Store{
        return $this->store;
    }

    public function getFullName(): string{
        return "$this->name $this->surname";
    }

}
