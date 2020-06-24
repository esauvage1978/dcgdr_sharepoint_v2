<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnderRubricRepository")
 */
class UnderRubric implements EntityInterface
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
     * @ORM\Column(type="boolean")
     */
    private $isEnable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $showOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rubric", inversedBy="underRubrics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rubric;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Picture", inversedBy="underRubrics")
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UnderThematic", inversedBy="underrubrics", fetch="EAGER")
     */
    private $underThematic;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="underrubricReaders")
     * @ORM\JoinTable("underrubricreader_corbeille")
     */
    private $readers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="underrubricWriters")
     * @ORM\JoinTable("underrubricwriter_corbeille")
     */
    private $writers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowAll;


    public function __construct()
    {
        $this->showOrder=0;
        $this->backpacks = new ArrayCollection();
        $this->readers = new ArrayCollection();
        $this->writers = new ArrayCollection();
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

    public function getFullName(): ?string
    {
        return $this->getRubric()->getName() . ' > ' . $this->name;
    }

    public function getIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

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

    public function getShowOrder(): ?int
    {
        return $this->showOrder;
    }

    public function setShowOrder(int $showOrder): self
    {
        $this->showOrder = $showOrder;

        return $this;
    }

    public function getRubric(): ?Rubric
    {
        return $this->rubric;
    }

    public function setRubric(?Rubric $rubric): self
    {
        $this->rubric = $rubric;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getUnderThematic(): ?UnderThematic
    {
        return $this->underThematic;
    }

    public function setUnderThematic(?UnderThematic $underThematic): self
    {
        $this->underThematic = $underThematic;

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    public function addReader(Corbeille $reader): self
    {
        if (!$this->readers->contains($reader)) {
            $this->readers[] = $reader;
        }

        return $this;
    }

    public function removeReader(Corbeille $reader): self
    {
        if ($this->readers->contains($reader)) {
            $this->readers->removeElement($reader);
        }

        return $this;
    }

    /**
     * @return Collection|Corbeille[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Corbeille $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
        }

        return $this;
    }

    public function removeWriter(Corbeille $writer): self
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
        }

        return $this;
    }

    public function getIsShowAll(): ?bool
    {
        return $this->isShowAll;
    }

    public function setIsShowAll(bool $isShowAll): self
    {
        $this->isShowAll = $isShowAll;

        return $this;
    }


}
