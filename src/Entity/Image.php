<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{

    public const SAVE_TO =['database','folder'];

//    https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
    public const MIME_TYPES = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/x-icon",
        "image/svg",
        "image/svg+xml",
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $dataBase64;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(
     *     choices=Image::MIME_TYPES,
     *     message="File saving failed. Allowed file extensions are :.jpg, .gif, .ico, .png, .svg, .pdf"
     * )
     */
    private $mimeType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientOriginalName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $folder;

    /**
     *
     * @Assert\Choice(
     *     choices=Image::SAVE_TO,
     *     message="File saving failed. The location chosen for recording is not authorized"
     * )
     */
    public $saveTo;


    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="images")
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function setImage(UploadedFile $file=null): self
    {
        //dd(['file'=>$file]);
        if($file){
            $this->image = $file;
            $this->clientOriginalName = $file->getClientOriginalName();
            $this->createdAt = new \DateTime();
            $this->mimeType = $file->getMimeType();
            $this->imageName = uniqid() .
                $this->createdAt->format('_Y-m-d_h-i-s.') .
                $file->guessClientExtension();
            if($this->saveTo =="database"){
               $this->dataBase64 = base64_encode(\file_get_contents($file));
            }
        }

        return $this;
    }

    public function getDataBase64()
    {
        return $this->dataBase64;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getClientOriginalName(): ?string
    {
        return $this->clientOriginalName;
    }


    public function getFolder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(?string $folder): self
    {
            $this->folder = $folder;

        return $this;
    }


    public function getImageName():?string
    {
        return $this->imageName;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
