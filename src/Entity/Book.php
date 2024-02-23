<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    private ?string $year = null;


    /** Many Books have one Publisher. This is the owning side. */
    #[ManyToOne(targetEntity: Publisher::class, inversedBy: 'books')]
    #[JoinColumn(name: 'publisher_id', referencedColumnName: 'id')]
    private Publisher|null $publisher = null;


    /**
     * Many Books have Many Authors.
     * @var Collection<int, Author>
     */
    #[ManyToMany(targetEntity: Author::class, mappedBy: 'books')]
    private Collection $authors;
    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }



    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);
        $author->removeBook($this);

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }

    public function setPublisher($publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

}
