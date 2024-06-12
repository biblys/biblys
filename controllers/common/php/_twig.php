<?php

    $e404 = false;

    //** GET VARIABLES **//

    $v = [];

    if ($_PAGE == 'article') {
        $articles = $_SQL->prepare('SELECT * FROM `articles` JOIN `collections` USING(`collection_id`) WHERE `article_url` = :url LIMIT 1');
        $articles->execute(['url' => $_GET['url']]);

        if ($a = $articles->fetch(PDO::FETCH_ASSOC)) {
            redirect($urlgenerator->generate('article_show', ['slug' => $a['article_url']]), null, null, 301);
        } else {
            $e404 = true;
        }
    }

    if ($e404) {
        $_ECHO .= e404();
    } else {
        //** RENDER FILE **/

        $loader = new Twig_Loader_Filesystem('/');
        $twig = new Twig_Environment($loader, ['debug' => true]);
        $twig->addExtension(new Twig_Extension_Debug());

        // Get articles
        $getArticles = new Twig\TwigFunction('getArticles', function (array $where = [], array $options = []) {
            $_A = new ArticleManager();
            $articles = $_A->getAll($where, $options);

            return $articles;
        });
        $twig->addFunction($getArticles);

        // Render Page
        $renderPage = new Twig\TwigFunction('renderPage', function ($page_id) {
            return render_page($page_id, 'include');
        });
        $twig->addFunction($renderPage);

        // datefr()
        $filter = new Twig\TwigFilter('datefr', '_date');
        $twig->addFilter($filter);

        // price()
        $filter = new Twig\TwigFilter('price', 'price');
        $twig->addFilter($filter);

        // Format an array of author entities
        $filter_author_list = new Twig\TwigFilter('author_list', function ($authors) {
            $list = null;
            for ($i = 0, $c = count($authors); $i < $c; ++$i) {
                $a = $authors[$i];
                $list .= '<a href="/'.$a->get('url').'/">'.$a->get('first_name').' '.$a->get('last_name').'</a>';

                // Connector
                if ($i == ($c - 2)) {
                    $list .= ' et ';
                } elseif ($i < ($c - 1)) {
                    $list .= ', ';
                }
            }

            return $list;
        });
        $twig->addFilter($filter_author_list);

        if (filesize($_HTML)) {
            try {
                $template = $twig->loadTemplate($_HTML);

                $_ECHO .= $template->render($v);
            } catch (Exception $e) {
                trigger_error($e->getMessage());
            }
        } else {
            trigger_error('Template HTML vide.');
        }
    }
