<?php


namespace App\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadArrayFiles
{


//    https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
    public const MIME_TYPES = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/x-icon",
        "image/svg",
        "image/svg+xml",
//        "text/plain",
//        "text/csv",
        "application/pdf",
        "application/x-pdf",
//        "application/msword",
//        "application/vnd.openxmlformats-",
//        "officedocument.wordprocessingml.document",
//        "application/vnd.oasis.opendocument.text",
//        "application/vnd.oasis.opendocument.spreadsheet",
//        "application/vnd.ms-excel",
//        "application/vnd.openxmlformats-",
//        "officedocument.spreadsheetml.sheet",
//        "application/xml",
//        "application/json",
//        "application/zip",
    ];

    private $files;
    /**
     *@Assert\EqualTo("true", message="At least one of files is not valid. Allowed file extensions are :.jpg, .gif, .ico, .png, .svg, .pdf")
     */
    private $uploaded = true;


    private $saveTo;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getSaveTo(): ?string
    {
        return $this->saveTo;
    }

    /**
     * @return Collection|UploadedFile[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function setSaveTo(?string $saveTo)
    {
        $this->saveTo = $saveTo;
        return $this;
    }


    public function setFiles(UploadedFile $files = null): self
    {
        $this->files = $files;

        return $this;
    }


    public function addFile(UploadedFile $file): self
    {
        if (!$this->files->contains($file)) {
            if( !in_array($file->getMimeType(),$this::MIME_TYPES) ){
                $this->uploaded = false;
            }
            $this->files[] = $file;
        }

        return $this;
    }

    public function removeFile(UploadedFile $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
        }

        return $this;
    }

    public function getUploaded():bool
    {
        return $this->uploaded|false;
    }
}
