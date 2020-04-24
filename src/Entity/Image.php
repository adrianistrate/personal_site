<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */

    private $project;

    /**
     * Unmapped property to handle file uploads
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_main;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $frame;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @var
     */
    private $old_filename;

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
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Image
     */
    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     * @return Image
     */
    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->refreshUpdated();
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire.
     */
    public function refreshUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return bool|null
     */
    public function getIsMain(): ?bool
    {
        return $this->is_main;
    }

    /**
     * @param bool $is_main
     * @return Image
     */
    public function setIsMain(bool $is_main): self
    {
        $this->is_main = $is_main;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFrame(): ?int
    {
        return $this->frame;
    }

    /**
     * @param int|null $frame
     * @return Image
     */
    public function setFrame(?int $frame): self
    {
        $this->frame = $frame;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeInterface $created_at
     * @return Image
     */
    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTimeInterface $updated_at
     * @return Image
     */
    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     *
     */
    public function prepareOldFile()
    {
        $this->old_filename = $this->filename;
    }

    /**
     * @return mixed
     */
    public function getOldFilename()
    {
        return $this->old_filename;
    }

    /**
     * @param mixed $old_filename
     */
    public function setOldFilename($old_filename): void
    {
        $this->old_filename = $old_filename;
    }
}
