<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(fields="reference", message="This reference is already used.")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     *
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Regex(
     *     pattern="/^([A-Z0-9\-_]+)$/m",
     *     htmlPattern = "^([A-Z0-9\-_]+)$",
     *     match=true,
     *     message="The reference of category is not valid"
     * )
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * The price must be between 0 and 1000
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Stored value
     *
     * @ORM\Column(type="integer")
     */
    private $storedValue;

    /**
     * Stock alert threshold for this product to trigger an alarm
     * @ORM\Column(type="integer", nullable=true)
     */
    private $storedAlarm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="product", cascade={"persist", "remove"})
     */
    private $images;


    /**
     *
     * @Assert\Choice(
     *     choices=Image::SAVE_TO,
     *     message="File saving failed. The location chosen for recording is not authorized"
     * )
     */
    private $saveTo;

    /**
     * @var string
     */
    private $files;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        // $this->files = new ArrayCollection();

    }


    public function getFiles()
    {
        return $this->files;
    }


    public function setFiles($files)
    {
         dd(['setFiles' => $files, 'this'=>$this]);
        foreach ($files as $file) {
            $image = new Image();
            $image->setImage($file);
            $image->setSaveTo($this->saveTo);
            dump(['setFiles_image' => $image, 'this' => $image == null]);
            if (
                $image->getImage() != null
                && $image->getSaveTo() == 'folder')
            {
                $image->setFolder();
            }
            try {
                $this->addImage($image);
            } catch (\Exception $e) {
                dd(['setFiles error' => $e]);
            }
        }

        // dump(['setFiles' => $this]);
        return $this;
    }

    /**
     * @return mixed
     */
    public
    function getSaveTo()
    {
        return $this->saveTo;
    }

    /**
     * @param mixed $saveTo
     * @return Product
     */
    public
    function setSaveTo(?string $saveTo): self
    {
        $this->saveTo = $saveTo;
        return $this;
    }


    public
    function getId(): ?int
    {
        return $this->id;
    }

    public
    function getReference(): ?string
    {
        return $this->reference;
    }

    public
    function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public
    function getName(): ?string
    {
        return $this->name;
    }

    public
    function setName(string $name): self
    {
        if ($name) {
            if ($this->createdAt) {
                $this->updatedAt = new \DateTime();
            } else {
                $this->createdAt = new \DateTime();
            }
            $this->reference = intval((new \DateTime())->format('YmdHis')) * 100 + random_int(0, 99);
            $this->name = $name;
        }

        return $this;
    }

    public
    function getDescription(): ?string
    {
        return $this->description;
    }

    public
    function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public
    function getPrice(): ?string
    {
        return $this->price;
    }

    public
    function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public
    function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public
    function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public
    function getStoredValue(): ?int
    {
        return $this->storedValue;
    }

    public
    function setStoredValue(int $storedValue): self
    {
        $this->storedValue = $storedValue;

        return $this;
    }

    public
    function getStoredAlarm(): ?int
    {
        return $this->storedAlarm;
    }

    public
    function setStoredAlarm(?int $storedAlarm): self
    {
        $this->storedAlarm = $storedAlarm;

        return $this;
    }

    public
    function getPublished(): ?bool
    {
        return $this->published;
    }

    public
    function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public
    function getCategory(): ?Category
    {
        return $this->category;
    }

    public
    function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public
    function getImages(): Collection
    {
        return $this->images;
    }

    public
    function addImage(Image $image): self
    {
        // dd(['addImage'=>$image]);

        if (!in_array($image->getMimeType(), Image::MIME_TYPES)){
            throw new \Exception("File saving failed. Allowed file extensions are :.jpg, .gif, .ico, .png, .svg, .pdf");
        }

        if (!$this->images->contains($image)) {
            $image->saveTo();
            $this->images[] = $image;

            $image->setProduct($this);
            //dd(['addImage'=>$image]);
        }
        return $this;
    }


    public
    function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);

            $current_dir_path = getcwd();
            $folder = $current_dir_path . '\\' . str_replace('/', '\\', $image->getFolder());
            if (!empty($image->getFolder())) {
                chdir($folder);
                try {
                    unlink($image->getImageName());
                } catch (\Exception $e) {
                    throw new \Exception($e);
                }
                chdir($current_dir_path);
            }

            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public
    function getStarRating()
    {
        $random = random_int(0, 60);
        // dump([ ($random < 51) ? $random / 10 : null]);
        return ($random < 51) ? $random / 10 : null;
    }


}
