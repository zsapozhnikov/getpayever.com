<?php
/**
 * This file is part of test task
 */

namespace AppBundle\Controller;

use AppBundle\Service\AlbumService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * @author Evgeny Sapozhnikov <zsapozhnikov@gmail.com>
 */
class AlbumController extends Controller
{
    /**
     * @Route("/albums", name="albums")
     */
    public function indexAction()
    {
        /** @var AlbumService $albumService */
        $albumService = $this->get('service.album');

        return new Response($albumService->getAllAlbums());
    }
}
