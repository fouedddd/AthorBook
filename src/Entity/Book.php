<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tittle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publicationdate = null;

    #[ORM\Column]
    private ?bool $publiched = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Author $author = null;

   
    

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $category = null;

    public function getId():?int
    {
        return $this->id;
    }
    public function setId(int $id):static
    {
        $this->id = $id;
        return $this;
    }

    public function getTittle(): ?string
    {
        return $this->tittle;
    }

    public function setTittle(string $tittle): static
    {
        $this->tittle = $tittle;

        return $this;
    }

    public function getPublicationdate(): ?\DateTimeInterface
    {
        return $this->publicationdate;
    }

    public function setPublicationdate(?\DateTimeInterface $publicationdate): static
    {
        $this->publicationdate = $publicationdate;

        return $this;
    }

    public function isPubliched(): ?bool
    {
        return $this->publiched;
    }

    public function setPubliched(bool $publiched): static
    {
        $this->publiched = $publiched;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

   

   

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }
}
