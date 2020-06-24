<?php

namespace App\Entity;

use App\Repository\CorbeilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CorbeilleRepository::class)
 */
class Corbeille implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
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
     * @ORM\Column(type="boolean")
     */
    private $isShowRead;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShowWrite;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="corbeilles")
     * @ORM\OrderBy({"name" = "ASC"})
     *
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Organisme::class, inversedBy="corbeilles")
     */
    private $organisme;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Rubric", mappedBy="readers")
     * @ORM\JoinTable("rubricreader_corbeille")
     */
    private $rubricReaders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Rubric", mappedBy="writers")
     * @ORM\JoinTable("rubricwriter_corbeille")
     */
    private $rubricWriters;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UnderRubric", mappedBy="readers")
     * @ORM\JoinTable("underrubricreader_corbeille")
     */
    private $underrubricReaders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UnderRubric", mappedBy="writers")
     * @ORM\JoinTable("underrubricwriter_corbeille")
     */
    private $underrubricWriters;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->rubricReaders = new ArrayCollection();
        $this->rubricWriters = new ArrayCollection();
        $this->underrubricReaders = new ArrayCollection();
        $this->underrubricWriters = new ArrayCollection();
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
        return (null !== $this->organisme) ?
            $this->getOrganisme()->getRef().' - '.$this->getName() :
            $this->getName();
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

    public function getIsShowRead(): ?bool
    {
        return $this->isShowRead;
    }

    public function setIsShowRead(bool $isShowRead): self
    {
        $this->isShowRead = $isShowRead;

        return $this;
    }

    public function getIsShowWrite(): ?bool
    {
        return $this->isShowWrite;
    }

    public function setIsShowWrite(bool $isShowWrite): self
    {
        $this->isShowWrite = $isShowWrite;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getOrganisme(): ?Organisme
    {
        return $this->organisme;
    }

    public function setOrganisme(?Organisme $organisme): self
    {
        $this->organisme = $organisme;

        return $this;
    }

    /**
     * @return Collection|Rubric[]
     */
    public function getRubricReaders(): Collection
    {
        return $this->rubricReaders;
    }

    public function addRubricReader(Rubric $rubricReaders): self
    {
        if (!$this->rubricReaders->contains($rubricReaders)) {
            $this->rubricReaders[] = $rubricReaders;
            $rubricReaders->addReader($this);
        }

        return $this;
    }

    public function removeRubricReader(Rubric $rubricReaders): self
    {
        if ($this->rubricReaders->contains($rubricReaders)) {
            $this->rubricReaders->removeElement($rubricReaders);
            $rubricReaders->removeReader($this);
        }

        return $this;
    }

    /**
     * @return Collection|Rubric[]
     */
    public function getRubricWriters(): Collection
    {
        return $this->rubricWriters;
    }

    public function addRubricWriter(Rubric $rubricWriters): self
    {
        if (!$this->rubricWriters->contains($rubricWriters)) {
            $this->rubricWriters[] = $rubricWriters;
            $rubricWriters->addWriter($this);
        }

        return $this;
    }

    public function removeRubricWriter(Rubric $rubricWriters): self
    {
        if ($this->rubricWriters->contains($rubricWriters)) {
            $this->rubricWriters->removeElement($rubricWriters);
            $rubricWriters->removeWriter($this);
        }

        return $this;
    }

    /**
     * @return Collection|UnderRubric[]
     */
    public function getUnderRubricReaders(): Collection
    {
        return $this->underrubricReaders;
    }

    public function addUnderRubricReader(UnderRubric $underrubricReaders): self
    {
        if (!$this->underrubricReaders->contains($underrubricReaders)) {
            $this->underrubricReaders[] = $underrubricReaders;
            $underrubricReaders->addReader($this);
        }

        return $this;
    }

    public function removeUnderRubricReader(UnderRubric $underrubricReaders): self
    {
        if ($this->underrubricReaders->contains($underrubricReaders)) {
            $this->underrubricReaders->removeElement($underrubricReaders);
            $underrubricReaders->removeReader($this);
        }

        return $this;
    }

    /**
     * @return Collection|UnderRubric[]
     */
    public function getUnderRubricWriters(): Collection
    {
        return $this->underrubricWriters;
    }

    public function addUnderRubricWriter(UnderRubric $underrubricWriters): self
    {
        if (!$this->underrubricWriters->contains($underrubricWriters)) {
            $this->underrubricWriters[] = $underrubricWriters;
            $underrubricWriters->addWriter($this);
        }

        return $this;
    }

    public function removeUnderRubricWriter(UnderRubric $underrubricWriters): self
    {
        if ($this->underrubricWriters->contains($underrubricWriters)) {
            $this->underrubricWriters->removeElement($underrubricWriters);
            $underrubricWriters->removeWriter($this);
        }

        return $this;
    }
}
