<?php

namespace AppBundle\Controller;

use Framework\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SiteController extends Controller
{

    public function __construct()
    {
        global $urlgenerator, $_V, $_SITE, $session;

        $this->sm = new \SiteManager();
        $this->om = new \OptionManager();

        $this->site = $this->sm->getById($_SITE['site_id']);
        $this->user = $_V;
        $this->url = $urlgenerator;
        $this->session = $session;
    }

    public function optionsAction(Request $request)
    {
        global $site;

        $this->auth('admin');
        $this->setPageTitle('Options du site');

        if ($request->getMethod() == "POST") {

            // Add a new option
            $new_key = $request->request->get('new_key');
            $new_val = $request->request->get('new_value');
            if (!empty($new_key) && !empty($new_val)) {
                $this->site->setOpt($new_key, $new_val);
                $this->session->getFlashbag()->add('success', "L'option &laquo;&nbsp;".$new_key."&nbsp;&raquo; a été ajoutée.");
            }

            // Update existing options
            $options = $request->request->get('options', []);
            foreach ($options as $key => $val) {
                $this->site->setOpt($key, $val);
            }

            return new RedirectResponse($this->url->generate('site_options'));
        }

        $options = $this->om->getAll(['site_id' => $site->get('id'), 'user_id' => 'NULL'], ['order' => 'option_key']);

        return $this->render('AppBundle:Site:options.html.twig', [
            'options' => $options
        ]);
    }


    public function defaultValuesAction(Request $request)
    {
        $this->auth('admin');
        $this->setPageTitle('Valeurs par défaut');

        if ($request->getMethod() == "POST") {

            $options = $request->request->get('options', []);
            foreach ($options as $key => $val) {
                $this->site->setOpt($key, $val);
            }

            return new RedirectResponse($this->url->generate('site_default_values'));
        }

        return $this->render('AppBundle:Site:defaultValues.html.twig', [
            'type_options' => \Biblys\Article\Type::getOptions($this->site->getOpt('default_type_id')),
            'options' => [
                'default_collection_id' => $this->site->getOpt('default_collection_id'),
                'default_article_source_id' => $this->site->getOpt('default_article_source_id'),
                'default_article_pubdate' => $this->site->getOpt('default_article_pubdate'),
                'default_article_tags' => $this->site->getOpt('default_article_tags'),
                'default_stock_invoice' => $this->site->getOpt('default_stock_invoice'),
                'default_stock_stockage' => $this->site->getOpt('default_stock_stockage'),
                'default_stock_purchase_price' => $this->site->getOpt('default_stock_purchase_price'),
                'default_stock_selling_price' => $this->site->getOpt('default_stock_selling_price'),
                'default_stock_condition' => $this->site->getOpt('default_stock_condition'),
                'default_stock_condition_details' => $this->site->getOpt('default_stock_condition_details'),
                'default_stock_discount' => $this->site->getOpt('default_stock_discount'),
                'default_stock_super_discount' => $this->site->getOpt('default_stock_super_discount'),
                'default_stock_cascading_discount' => $this->site->getOpt('default_stock_cascading_discount'),
                'default_stock_purchase_date' => $this->site->getOpt('default_stock_purchase_date')
            ]
        ]);
    }

}
