<?php
/**
 * This file is part of test task
 */

namespace AppBundle\Service;

use AppBundle\Entity\Album;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @DI\Service("service.album")
 *
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class AlbumService
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * @DI\InjectParams({
     *     "entityManager" = @DI\Inject("doctrine.orm.default_entity_manager"),
     *     "objectNormalizer" = @DI\Inject("object_normalizer"),
     *     "jsonEncoder" = @DI\Inject("json_encoder")
     * })
     *
     * @param ObjectManager $entityManager
     * @param ObjectNormalizer $objectNormalizer
     * @param JsonEncoder $jsonEncoder
     */
    public function __construct(
        ObjectManager $entityManager,
        ObjectNormalizer $objectNormalizer,
        JsonEncoder $jsonEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->objectNormalizer = $objectNormalizer;
        $this->jsonEncoder = $jsonEncoder;
    }

    public function getAllAlbums()
    {
        $this->objectNormalizer->setCircularReferenceHandler(function (Album $object) {
            return $object->getId();
        });

        $serializer = new Serializer([$this->objectNormalizer], [$this->jsonEncoder]);

        $albums = $this->entityManager->getRepository(Album::class)->findAllAlbums();

        return $serializer->serialize($albums, JsonEncoder::FORMAT);
    }
}
