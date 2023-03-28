<?php

namespace AppBundle\Controller;

use Framework\Controller;
use GalleryManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GalleryController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function indexAction(): Response
    {
        $gm = new GalleryManager();

        $galleries = $gm->getAll([], [
            'order' => 'gallery_created',
            'sort' => 'desc',
        ]);

        return $this->render('AppBundle:Gallery:index.html.twig', [
            'galleries' => $galleries,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     */
    public function showAction($slug): Response
    {
        $gm = new GalleryManager();

        $gallery = $gm->get(['media_dir' => $slug]);
        if (!$gallery) {
            throw new NotFoundException('Cannot find gallery with slug '.$slug);
        }

        return $this->render('AppBundle:Gallery:show.html.twig', [
            'gallery' => $gallery,
        ]);
    }
}
