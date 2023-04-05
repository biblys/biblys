<?php

namespace AppBundle\Controller;

use Biblys\Article\Type;
use Biblys\Service\CurrentSite;
use Framework\Controller;

use Framework\Exception\AuthException;
use Model\OptionQuery;
use OptionManager;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SiteController extends Controller
{

    public function __construct()
    {
        global $urlgenerator, $_V;

        $this->user = $_V;
        $this->url = $urlgenerator;

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param Session $session
     * @param CurrentSite $currentSite
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function optionsAction(Request $request, Session $session, CurrentSite $currentSite): RedirectResponse|Response
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Options du site");

        if ($request->getMethod() == "POST") {

            // Add a new option
            $new_key = $request->request->get('new_key');
            $new_val = $request->request->get('new_value');
            if (!empty($new_key) && !empty($new_val)) {
                $currentSite->setOption($new_key, $new_val);
                $session->getFlashbag()->add(
                    "success",
                    "L'option &laquo;&nbsp;".$new_key."&nbsp;&raquo; a été ajoutée."
                );
            }

            // Update existing options
            $options = $request->request->all("options");
            foreach ($options as $key => $val) {
                $currentSite->setOption($key, $val);
            }

            return new RedirectResponse($this->url->generate("site_options"));
        }

        $options = OptionQuery::create()
            ->filterBySite($currentSite->getSite())
            ->filterByUserId()
            ->orderByKey();
        return $this->render("AppBundle:Site:options.html.twig", [
            "options" => $options
        ]);
    }


    /**
     * @throws SyntaxError
     * @throws AuthException
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function defaultValuesAction(Request $request, CurrentSite $currentSite)
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Valeurs par défaut");

        if ($request->getMethod() == "POST") {

            $options = $request->request->all('options');
            foreach ($options as $key => $val) {
                $currentSite->setOption($key, $val);
            }

            return new RedirectResponse($this->url->generate('site_default_values'));
        }

        return $this->render('AppBundle:Site:defaultValues.html.twig', [
            'type_options' => Type::getOptions($currentSite->getOption('default_type_id')),
            'options' => [
                'default_collection_id' => $currentSite->getOption('default_collection_id'),
                'default_article_source_id' => $currentSite->getOption('default_article_source_id'),
                'default_article_pubdate' => $currentSite->getOption('default_article_pubdate'),
                'default_article_tags' => $currentSite->getOption('default_article_tags'),
                'default_stock_invoice' => $currentSite->getOption('default_stock_invoice'),
                'default_stock_stockage' => $currentSite->getOption('default_stock_stockage'),
                'default_stock_purchase_price' => $currentSite->getOption('default_stock_purchase_price'),
                'default_stock_selling_price' => $currentSite->getOption('default_stock_selling_price'),
                'default_stock_condition' => $currentSite->getOption('default_stock_condition'),
                'default_stock_condition_details' => $currentSite->getOption('default_stock_condition_details'),
                'default_stock_discount' => $currentSite->getOption('default_stock_discount'),
                'default_stock_super_discount' => $currentSite->getOption('default_stock_super_discount'),
                'default_stock_cascading_discount' => $currentSite->getOption('default_stock_cascading_discount'),
                'default_stock_purchase_date' => $currentSite->getOption('default_stock_purchase_date')
            ]
        ]);
    }

}
