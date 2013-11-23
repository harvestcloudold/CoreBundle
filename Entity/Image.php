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
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    public $filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $sub_dir;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    public $path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $extension;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $mime_type;

    /**
     * @ORM\Column(type="integer")
     */
    public $width;

    /**
     * @ORM\Column(type="integer")
     */
    public $height;

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
        return $this->getUploadBaseDir().'/'.$this->getSubDir().'/'.$this->getFilename();
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
    public function setFilename($filename)
    {
        $this->filename = $filename;

        $sub_dir = '';
        $sub_dir .= substr(0, 2, $filename).DIRECTORY_SEPARATOR;
        $sub_dir .= substr(2, 2, $filename).DIRECTORY_SEPARATOR;
        $sub_dir .= substr(4, 2, $filename);

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
}
