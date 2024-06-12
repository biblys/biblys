<?php

namespace AppBundle\Controller;

use Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TagController extends Controller
{

    /**
     * Show a Tag's page and related articles
     * /tag/{slug}
     * @param  $url the tag's slug
     * @return Response the rendered templated
     */
    public function showAction(Request $request, $slug)
    {
        $tm = $this->entityManager("Tag");
        $am = $this->entityManager("Article");

        $tag = $tm->get(["tag_url" => $slug]);
        if (!$tag) {
            throw new NotFoundException("Tag $slug not found");
        }

        $this->setPageTitle($tag->get('name'));

        // Pagination
        $page = (int) $request->query->get('p', 0);
        $totalCount = $am->countAllFromTag($tag);
        $pagination = new \Biblys\Utils\Pagination($page, $totalCount);

        $am = $this->entityManager("Article");
        $articles = $am->getAllFromTag($tag, [
            'fields' => 'article_id, article_title, article_url, article_authors, collection_id, publisher_id, type_id, article_pubdate, article_availability_dilicom, article_price',
            'order' => 'article_pubdate',
            'sort' => 'desc',
            'limit' => $pagination->getLimit(),
            'offset' => $pagination->getOffset()
        ]);

        return $this->render('AppBundle:Tag:show.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'pages' => $pagination
        ]);
    }

    /**
     * Edit a tag
     * /admin/tag/{id}/edit
     * @param  Request $request
     * @param  Int  $id the tag's id
     * @return Response
     */
    public function editAction(Request $request, $id)
    {
        global $site;

        $this->auth("admin");

        $tm = $this->entityManager("Tag");

        $tag = $tm->get(["tag_id" => $id]);
        if (!$tag) {
            throw new NotFoundException("Tag $id not found.");
        }

        $this->setPageTitle('Modifier l\'éditeur '.$tag->get('name'));

        $formFactory = $this->getFormFactory();

        $defaults = [
            'name' => $tag->get('name'),
            'description' => $tag->get('description')
        ];

        $form = $formFactory->createBuilder(FormType::class, $defaults)
            ->add('name', TextType::class, ['label' => 'Nom :', 'required' => false])
            ->add('description', TextareaType::class, ['label' => false, 'attr' => ['class' => 'wysiwyg']])
            ->getForm();

        $error = false;
        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);
            $data = $form->getData();

            $updated = clone $tag;
            $updated->set('tag_name', $data['name'])
                ->set('tag_description', $data['description']);

            try {
                $updated = $tm->update($updated);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (!$error) {
                return new RedirectResponse($this->generateUrl('tag_show', ['slug' => $updated->get('url')]));
            }
        }

        return $this->render('AppBundle:Tag:edit.html.twig', [
            'tag' => $tag,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }
}
