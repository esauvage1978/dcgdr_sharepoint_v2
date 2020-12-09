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
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $stateCurrent;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stateAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $stateContent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="backpacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BackpackState", mappedBy="backpack")
     */
    private $backpackStates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BackpackFile", mappedBy="backpack", orphanRemoval=true,cascade={"persist"})
     */
    private $backpackFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BackpackLink", mappedBy="backpack",cascade={"persist"})
     */
    private $backpackLinks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="backpack", orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="backpack", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->backpackStates = new ArrayCollection();
        $this->backpackFiles = new ArrayCollection();
        $this->backpackLinks = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStateCurrent(): ?string
    {
        return $this->stateCurrent;
    }

    public function setStateCurrent(string $stateCurrent): self
    {
        $this->stateCurrent = $stateCurrent;

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

    public function getStateAt(): ?\DateTimeInterface
    {
        return $this->stateAt;
    }

    public function setStateAt(\DateTimeInterface $stateAt): self
    {
        $this->stateAt = $stateAt;

        return $this;
    }

    public function getStateContent(): ?string
    {
        return $this->stateContent;
    }

    public function setStateContent(?string $stateContent): self
    {
        $this->stateContent = $stateContent;

        return $this;
    }



    /**
     * @return Collection|BackpackState[]
     */
    public function getBackpackStates(): Collection
    {
        return $this->backpackStates;
    }

    public function addBackpackState(BackpackState $backpackState): self
    {
        if (!$this->backpackStates->contains($backpackState)) {
            $this->backpackStates[] = $backpackState;
            $backpackState->setBackpack($this);
        }

        return $this;
    }

    public function removeBackpackState(BackpackState $backpackState): self
    {
        if ($this->backpackStates->contains($backpackState)) {
            $this->backpackStates->removeElement($backpackState);
            // set the owning side to null (unless already changed)
            if ($backpackState->getBackpack() === $this) {
                $backpackState->setBackpack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BackpackFile[]
     */
    public function getBackpackFiles(): Collection
    {
        return $this->backpackFiles;
    }

    public function addBackpackFile(BackpackFile $backpackFile): self
    {
        if (!$this->backpackFiles->contains($backpackFile)) {
            $this->backpackFiles[] = $backpackFile;
            $backpackFile->setBackpack($this);
        }

        return $this;
    }



    /**
     * @return Collection|BackpackLink[]
     */
    public function getBackpackLinks(): Collection
    {
        return $this->backpackLinks;
    }

    public function addBackpackLink(BackpackLink $backpackLink): self
    {
        if (!$this->backpackLinks->contains($backpackLink)) {
            $this->backpackLinks[] = $backpackLink;
            $backpackLink->setBackpack($this);
        }

        return $this;
    }

    public function removeBackpackLink(BackpackLink $backpackLink): self
    {
        if ($this->backpackLinks->contains($backpackLink)) {
            $this->backpackLinks->removeElement($backpackLink);
            // set the owning side to null (unless already changed)
            if ($backpackLink->getBackpack() === $this) {
                $backpackLink->setBackpack(null);
            }
        }

        return $this;
    }
    public function removeBackpackFile(BackpackFile $backpackFile): self
    {
        if ($this->backpackFiles->contains($backpackFile)) {
            $this->backpackFiles->removeElement($backpackFile);
            // set the owning side to null (unless already changed)
            if ($backpackFile->getBackpack() === $this) {
                $backpackFile->setBackpack(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setBackpack($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getBackpack() === $this) {
                $history->setBackpack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBackpack($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getBackpack() === $this) {
                $comment->setBackpack(null);
            }
        }

        return $this;
    }
}
