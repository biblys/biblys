<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Framework;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Exception;
use Opengraph\Writer;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Controller
{
    /**
     * Returns a Response with a rendered template.
     *
     * @deprecated Controller->render is deprecated. Use TemplateService->render instead.
     *
     * @param string $templatePath template file path
     * @param array $vars template variables
     *
     * @return Response a Response object containing the rendered template
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws Exception
     */
    public function render(string $templatePath, array $vars = []): Response
    {
        $config = Config::load();
        $request = Request::createFromGlobals();
        $currentSiteService = CurrentSite::buildFromConfig($config);
        $currentUserService = CurrentUser::buildFromRequestAndConfig($request, $config);
        $metaTagsService = new MetaTagsService(new Writer(), $currentSiteService);
        $templateService = new TemplateService(
            config: $config,
            currentSiteService: $currentSiteService,
            currentUserService: $currentUserService,
            metaTagsService: $metaTagsService,
            request: $request,
        );
        return $templateService->renderResponse($templatePath, $vars);
    }

    /**
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     * @deprecated Controller->redirect is deprecated. Use Symfony\Component\HttpFoundation\RedirectResponse instead.
     */
    public function redirect(string $url, int $status = 302): RedirectResponse
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.59.0",
            "Controller->redirect is deprecated. Use Symfony\Component\HttpFoundation\RedirectResponse instead."
        );

        return new RedirectResponse($url, $status);
    }

    /**
     * @throws Exception
     * @deprecated Controller->setOpengraphTags is deprecated. Use MetaTagsService instead.
     */
    public function setOpengraphTags($tags = []): void
    {
        trigger_deprecation(
            "biblys/biblys",
            "3.4.0",
            "Controller->setOpengraphTags is deprecated. Use MetaTagsService instead."
        );

        $request = LegacyCodeHelper::getGlobalRequest();

        if (!isset($tags['type'])) {
            $tags['type'] = 'website';
        }

        if (!isset($tags['site_name'])) {
            $globalSite = LegacyCodeHelper::getGlobalSite();
            $tags['site_name'] = $globalSite->get('title');
        }

        if (!isset($tags['locale'])) {
            $tags['locale'] = 'fr_FR';
        }

        if (!isset($tags['url'])) {
            $tags['url'] = $request->getUri();
        }

        $request->attributes->set('opengraph_tags', $tags);
    }

    public function setTwitterCardsTags($tags): void
    {
        $request = LegacyCodeHelper::getGlobalRequest();

        if (!isset($tags['card'])) {
            $tags['card'] = 'summary';
        }

        $request->attributes->set('twitter_cards_tags', $tags);
    }

    /**
     * Really generates an url from a route using the Routing component.
     *
     * @return string the generated url
     * @deprecated Calling Controller->generateUrl() is deprecated, inject UrlGenerator in the controller instead.
     */
    public function generateUrl(string $route, array $params = []): string
    {
        
        trigger_deprecation(
            "biblys/biblys",
            "2.59.0",
            "Calling Controller->generateUrl() is deprecated, inject UrlGenerator in the controller instead."
        );

        return \Biblys\Legacy\LegacyCodeHelper::getGlobalUrlGenerator()->generate($route, $params);
    }

    public function getFormFactory(): FormFactoryInterface
    {
        return Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();
    }
}
