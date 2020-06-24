<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RubricRepository")
 */
class Rubric implements EntityInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Thematic", inversedBy="rubrics", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thematic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Picture", inversedBy="rubrics")
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="rubricReaders")
     * @ORM\JoinTable("rubricreader_corbeille")
     */
    private $readers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Corbeille", inversedBy="rubricWriters")
     * @ORM\JoinTable("rubricwriter_corbeille")
     */
    private $writers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowAll;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UnderRubric", mappedBy="rubric", orphanRemoval=true)
     */
    private $underRubrics;

    public function __construct()
    {
        $this->showOrder=0;
        $this->readers = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->underRubrics = new ArrayCollection();
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

    public function getThematic(): ?Thematic
    {
        return $this->thematic;
    }

    public function setThematic(?Thematic $thematic): self
    {
        $this->thematic = $thematic;

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


    /**
     * @return Collection|UnderRubric[]
     */
    public function getUnderRubrics(): Collection
    {
        return $this->underRubrics;
    }

    public function addUnderRubric(UnderRubric $underRubric): self
    {
        if (!$this->underRubrics->contains($underRubric)) {
            $this->underRubrics[] = $underRubric;
            $underRubric->setRubric($this);
        }

        return $this;
    }

    public function removeUnderRubric(UnderRubric $underRubric): self
    {
        if ($this->underRubrics->contains($underRubric)) {
            $this->underRubrics->removeElement($underRubric);
            // set the owning side to null (unless already changed)
            if ($underRubric->getRubric() === $this) {
                $underRubric->setRubric(null);
            }
        }

        return $this;
    }

}
