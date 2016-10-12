<?php
/**
 * This file is part of test task
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gpe_image")
 *
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Album", inversedBy="images", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Album
     */
    private $album;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $sortOrder;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param Album $album
     * @return Image
     */
    public function setAlbum($album)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Image
     */
    public function setPath(string $path): Image
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Image
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     * @return Image
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }
}