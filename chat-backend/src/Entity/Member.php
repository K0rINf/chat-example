<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MemberRepository")
 */
class Member
{
    const STATUS_ONLINE = 1;
    const STATUS_OFFLINE = 0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $nickname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="createdBy", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat", inversedBy="members")
     */
    private $chat;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->messages = new ArrayCollection();
        $this->status = self::STATUS_OFFLINE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getCreatedBy() === $this) {
                $message->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Chat
     */
    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     *
     * @return $this
     */
    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     *
     * @return Member
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }
}
