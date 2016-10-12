<?php
/**
 * This file is part of test task
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Album;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class LoadAlbumData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var string
     */
    const ALBUM_REFERENCE_NAME = 'album';
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $album = new Album();
            $album->setTitle('Album ' . $i);
            $album->setSortOrder($i);

            $manager->persist($album);
            $manager->flush();

            $this->addReference(self::ALBUM_REFERENCE_NAME . $i, $album);
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}