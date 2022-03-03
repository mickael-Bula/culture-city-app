<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
// add this use for vichUploaderBundle
use Vich\UploaderBundle\Mapping\Annotation as Vich;
// add this to upload file type in class method
use Symfony\Component\HttpFoundation\File\File;




/**
 * 
 * @UniqueEntity(fields={"name"}, message="Un événement nommé {{ value }} existe déjà !")
 * 
 * Add this on top of the class for vichUploaderBundle
 * @Vich\Uploadable
 * 
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank (message = "Ce champ est requis !")
     * @ORM\Column(type="string", length=255)
     * @Groups({"events"})
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPremium;

    /**
     * @Assert\NotBlank (message = "Vous devez renseigner une date et une heure !")
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"events"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"events"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"events"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="event", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="events")
     * @Groups({"events"})
     */
    private $tags;

    /**
    * @Assert\NotBlank (message = "Vous devez choisir une catégorie pour votre évènement !")
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"events"})
     */
    private $user;

    /**
     * @Assert\NotBlank(message="Vous devez ajouter une image")
     * 
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Le fichier image est trop loud ({{ size }} {{ suffix }}). Le poids maxmum autorisé pour le fichier est de {{ limit }} {{ suffix }}",
     *     notFoundMessage = "Le fichier image n'a pas été trouvé ! Veuillez joindre à nouveau votre fichier image ! - (@assert)"
     * )
     * 
     * @Assert\Image(
     *     minWidth = "400",
     *     minWidthMessage = "La largeur de l'image est trop petite ({{ width }}px). La largeur minimale attendue est de {{ min_width }}px",   
     *     minHeight = "400",
     *     minHeightMessage = "La hauteur de l'image est trop petite ({{ height }}px). La largeur minimale attendue est de {{ min_height }}px",
     *     mimeTypes = {"image/jpeg", "image/png","image/jpg", "image/gif"},
     *     mimeTypesMessage = "Uniqument les images de type .jpeg .png .jpg and .gif sont autorisés !"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"events"})
     */
    private $picture;

    /**
     * @Vich\UploadableField(mapping="event_picture", fileNameProperty="picture")
     * @var File
     */
    private $pictureFile;


    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }


    /**
     * Set the value of pictureFile
     * @param  File  $pictureFile
     * @return  self
     */ 
    public function setPictureFile(File $pictureFile = null)
    {   

        // picture correspond ici à pictureFile (donc au fichier)
        // picture en bdd prendra donc la valeur du nom du fichier
        // et pictureFile sera le fichier stocké dans les dossiers paramètrés dans 
        // vich_uploader.yaml et services.yaml 

        $this->picture = $pictureFile;

        // si il y a un fichier picture uploadé on met a jour la date sur updatedAt
        if ($pictureFile) {

            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get the value of pictureFile
     * @return  File
     */ 
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        if ($name) {

            $this->createdAt = new \DateTimeImmutable('now');
        }
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getIsPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(?bool $isPremium): self
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setEvent($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getEvent() === $this) {
                $post->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
