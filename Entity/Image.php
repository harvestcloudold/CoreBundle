<?php

/*
 * This file is part of the Harvest Cloud package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HarvestCloud\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image Entity
 *
 * Inspired by symfony.com/doc/current/cookbook/doctrine/file_uploads.html
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2013-11-22
 *
 * @ORM\Entity
 * @ORM\Table(name="image")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    protected $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $sub_dir;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    protected $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $extension;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $mime_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $height;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $original_filename;

    /**
     * __construct()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @param  string $extension
     */
    public function __construct($extension)
    {
        $this->setExtension($extension);
        $this->setFilename(md5(time().rand(0,100000).$extension).'.'.$this->getExtension());
    }

    /**
     * createFromUploadedFile()
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return \HarvestCloud\CoreBundle\Entity\Image
     */
    public static function createFromUploadedFile(UploadedFile $file)
    {
        $size = getimagesize($file->getRealPath());

        $image = new Image($file->guessExtension());
        $image->setOriginalFilename($file->getClientOriginalName());
        $image->setMimeType($file->getMimeType());
        $image->setSize(filesize($file->getRealPath()));
        $image->setWidth($size[0]);
        $image->setHeight($size[1]);

        return $image;
    }

    /**
     * getUploadPath()
     *
     * e.g. /var/www/www.harvestcloud.com/app/data/media/images/fh/dg/st/fhdgstetenbd.jpg
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getUploadPath()
    {
        return $this->getUploadDir().'/'.$this->getFilename();
    }

    /**
     * getUploadDir()
     *
     * e.g. /var/www/www.harvestcloud.com/app/data/media/images/fh/dg/st
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getUploadDir()
    {
        return $this->getUploadBaseDir().'/'.$this->getSubDir();
    }

    /**
     * getUploadBaseDir()
     *
     * Get the base directory of where the uploaded images are saved
     *
     * e.g. /var/www/www.harvestcloud.com/app/data/images
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    protected function getUploadBaseDir()
    {
        return __DIR__.'/../../../../app/data/media/images';
    }

    /**
     * Get id
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set filename
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  string $filename
     *
     * @return Image
     */
    public function setFilename($filename = null)
    {
        $this->filename = $filename;

        $sub_dir = '';
        $sub_dir .= substr($filename, 0, 2).DIRECTORY_SEPARATOR;
        $sub_dir .= substr($filename, 2, 2).DIRECTORY_SEPARATOR;
        $sub_dir .= substr($filename, 4, 2);

        $this->setSubDir($sub_dir);
        $this->setPath($sub_dir.DIRECTORY_SEPARATOR.$filename);

        return $this;
    }

    /**
     * Get filename
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set sub_dir
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  string $subDir
     *
     * @return Image
     */
    public function setSubDir($subDir)
    {
        $this->sub_dir = $subDir;

        // ensure directory exists
        if (!mkdir($this->getUploadDir(), 0755, true))
        {
            throw new \Exception('Could not create directory '.$this->getUploadDir());
        }

        return $this;
    }

    /**
     * Get sub_dir
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getSubDir()
    {
        return $this->sub_dir;
    }

    /**
     * Set extension
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  string $extension
     *
     * @return Image
     */
    public function setExtension($extension)
    {
        if ('jpeg' == $extension)
        {
            $extension = 'jpg';
        }

        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set mime_type
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  string $mimeType
     *
     * @return Image
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;

        return $this;
    }

    /**
     * Get mime_type
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Set width
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  integer $width
     *
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @param  integer $height
     *
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-22
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set path
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @param  string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set size
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @param  integer $size
     *
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set original_filename
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @param  string $originalFilename
     *
     * @return Image
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->original_filename = $originalFilename;

        // Let's default to the client's filename
        $this->setName($originalFilename);

        return $this;
    }

    /**
     * Get original_filename
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2013-11-23
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->original_filename;
    }
}
