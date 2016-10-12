<?php
/**
 * This file is part of test task
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


/**
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class LoadImageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var string
     */
    const ALBUMS_PATH = '/bundles/app/images/album';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();

        for ($i = 1; $i <= 5; $i++) {
            $albumDirectoryRelativePath = self::ALBUMS_PATH . DIRECTORY_SEPARATOR . $i;
            $albumDirectoryAbsolutePath = $this->container->getParameter('web_dir') . $albumDirectoryRelativePath;
            $finder->files()->in($albumDirectoryAbsolutePath);
            $sortOrder = 1;

            /** @var SplFileInfo $file */
            foreach ($finder as $file) {
                $image = new Image();
                $image->setPath($albumDirectoryRelativePath . DIRECTORY_SEPARATOR . $file->getRelativePathname());
                $image->setTitle($file->getRelativePathname());
                $image->setSortOrder($sortOrder++);
                $image->setAlbum($this->getReference(LoadAlbumData::ALBUM_REFERENCE_NAME . $i));
                $manager->persist($image);
            }
            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 2;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}