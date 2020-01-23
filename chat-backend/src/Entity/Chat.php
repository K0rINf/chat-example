<?php

namespace App\Entity;

use App\Request\DTO\CreateChatDto;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
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
    private $title;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Member", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Member", mappedBy="chat", orphanRemoval=true)
     */
    private $members;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $link): self
    {
        if (!$this->members->contains($link)) {
            $this->members[] = $link;
            $link->setChat($this);
        }

        return $this;
    }

    public function removeMember(Member $link): self
    {
        if ($this->members->contains($link)) {
            $this->members->removeElement($link);
            if ($link->getChat() === $this) {
                $link->setChat(null);
            }
        }

        return $this;
    }

    /**
     * @return Member|null
     */
    public function getCreatedBy(): ?Member
    {
        return $this->createdBy;
    }

    /**
     * @param Member $createdBy
     */
    public function setCreatedBy(Member $createdBy): void
    {
        $this->createdBy = $createdBy;
    }
}
