<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadArrayFiles
{


    private $files;

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

        if($files) {
            dump(['Count(files)'=>Count($files)]);
        }
        dump([
            'UploadArrayFiles this'=>$this,
        ]);

        return $this;
    }


    public function addFile(UploadedFile $file): self
    {
        if (!$this->files->contains($file)) {
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
}
