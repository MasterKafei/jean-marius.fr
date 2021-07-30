<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;

/**
 * @ORM\Entity(repositoryClass="EventRepository::class")
 */
class Event {

    /** 
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity="File")
     */
    private File $icon;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="string")
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $date;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIcon(): File
    {
        return $this->icon;
    }

    public function setIcon(File $icon): self
    {
        $this->icon = $icon;
    
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
    
        return $this;
    }
}