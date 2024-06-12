<?php

$em = new EventManager();

$use_old_controller = $site->getOpt('use_old_post_controller');

if ($e = $em->get(array('event_url' => filter_input(INPUT_GET, 'url')))) {

    // Unless site option is defined to use old event controller
    // redirect to to new event controller immediately
    $use_old_controller = $site->getOpt('use_old_post_controller');
    if (!$use_old_controller) {
        redirect(
            $urlgenerator->generate(
                'event_show',
                ['slug' => $e->get('url')]
            ),
            null,
            null,
            301
        );
    }

    if ($_V->isAdmin()) {
        $_ECHO .= '
                <div class="admin">
                    <p>&Eacute;v&egrave;nement n°' . $e->get('id') . '</p>
                    <p><a href="/pages/event_edit?id=' . $e->get('id') . '">modifier</a></p>
                    <p><a href="/pages/links?event_id=' . $e->get('id') . '">lier</a></p>
                    <p><a href="/pages/adm_guests?event_id=' . $e->get('id') . '">participants</a></p>
                </div>
            ';
    }

    $_PAGE_TITLE = $e->get('title');

    // Linked articles
    $the_articles = null;

    if ($html = get_template('event', array('event' => $e))) {
        $_ECHO .= $html;
    } else {
        $_ECHO .= e404();
    }
} else $_ECHO .= e404();
