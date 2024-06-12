<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class GalleryController extends Controller
{
    public function indexAction()
    {
        $gm = $this->entityManager('Gallery');

        $galleries = $gm->getAll([], [
            'order' => 'gallery_created',
            'sort' => 'desc',
        ]);

        return $this->render('AppBundle:Gallery:index.html.twig', [
            'galleries' => $galleries,
        ]);
    }

    public function showAction($slug)
    {
        $gm = $this->entityManager('Gallery');

        $gallery = $gm->get(['media_dir' => $slug]);
        if (!$gallery) {
            throw new NotFoundException('Cannot find gallery with slug '.$slug);
        }

        $this->setPageTitle($gallery->get('title'));

        return $this->render('AppBundle:Gallery:show.html.twig', [
            'gallery' => $gallery,
        ]);
    }
}
