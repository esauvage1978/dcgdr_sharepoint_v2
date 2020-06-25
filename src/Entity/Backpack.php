<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BackpackRepository")
 */
class Backpack implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;



    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UnderRubric", inversedBy="backpacks")
     */
    private $underRubric;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir3;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir4;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dir5;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $currentPlace;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="backpacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUnderRubric(): ?UnderRubric
    {
        return $this->underRubric;
    }

    public function setUnderRubric(?UnderRubric $underRubric): self
    {
        $this->underRubric = $underRubric;

        return $this;
    }

    public function getDir1(): ?string
    {
        return $this->dir1;
    }

    public function setDir1(?string $dir1): self
    {
        $this->dir1 = $dir1;

        return $this;
    }

    public function getDir2(): ?string
    {
        return $this->dir2;
    }

    public function setDir2(?string $dir2): self
    {
        $this->dir2 = $dir2;

        return $this;
    }

    public function getDir3(): ?string
    {
        return $this->dir3;
    }

    public function setDir3(?string $dir3): self
    {
        $this->dir3 = $dir3;

        return $this;
    }

    public function getDir4(): ?string
    {
        return $this->dir4;
    }

    public function setDir4(?string $dir4): self
    {
        $this->dir4 = $dir4;

        return $this;
    }

    public function getDir5(): ?string
    {
        return $this->dir5;
    }

    public function setDir5(?string $dir5): self
    {
        $this->dir5 = $dir5;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getCurrentPlace(): ?string
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace(string $currentPlace): self
    {
        $this->currentPlace = $currentPlace;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

}
