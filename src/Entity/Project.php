<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $started_on;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ended_on;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="project", orphanRemoval=true, cascade={"all"})
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $bg_color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $allow_see_more;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Project
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartedOn(): ?\DateTimeInterface
    {
        return $this->started_on;
    }

    /**
     * @param \DateTimeInterface|null $started_on
     * @return Project
     */
    public function setStartedOn(?\DateTimeInterface $started_on): self
    {
        $this->started_on = $started_on;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndedOn(): ?\DateTimeInterface
    {
        return $this->ended_on;
    }

    /**
     * @param \DateTimeInterface|null $ended_on
     * @return Project
     */
    public function setEndedOn(?\DateTimeInterface $ended_on): self
    {
        $this->ended_on = $ended_on;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Image $image
     * @return Project
     */
    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProject($this);
        }

        return $this;
    }

    /**
     * @param Image $image
     * @return Project
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProject() === $this) {
                $image->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBgColor(): ?string
    {
        return $this->bg_color;
    }

    /**
     * @param string|null $bg_color
     * @return Project
     */
    public function setBgColor(?string $bg_color): self
    {
        $this->bg_color = $bg_color;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Project
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Image|null
     */
    public function getMainImage()
    {
        $mainImage = null;
        foreach($this->images as $image) {
            if($image->getIsMain()) {
                $mainImage = $image;
            }
        }

        if(is_null($mainImage) && count($this->images)) {
            $mainImage = $this->images[0];
        }

        return $mainImage;
    }

    public function getAllowSeeMore(): ?bool
    {
        return $this->allow_see_more;
    }

    public function setAllowSeeMore(bool $allow_see_more): self
    {
        $this->allow_see_more = $allow_see_more;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }
}
