<?php

use Biblys\Exception\EntityAlreadyExistsException;
use Biblys\Exception\InvalidEntityException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$cm = new CycleManager();
$am = new ArticleManager();

$cycleId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$cycle = $cm->getById($cycleId);

if (!$cycle) {
    throw new ResourceNotFoundException("Cannot find any cycle with id $cycleId");
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $updateChildren = 0;
        foreach ($_POST as $key => $val) {
            $cycle->set($key, $val);
            if ($key == 'cycle_name') $updateChildren = 1;
        }

        $cycle->set('cycle_url', $cm->makeslug($cycle));

        $cm->update($cycle);

        // Update children
        if ($updateChildren) {
            $articles = $am->getAll(array('cycle_id' => $cycle->get('id')));
            $params['articles_updated'] = 0;
            foreach ($articles as $article) {
                $article->set('article_cycle', $cycle->get('name'));
                $am->update($article);
            }
        }

        new RedirectResponse("gi/serie/".$cycle->get('url'));
    } catch (EntityAlreadyExistsException | InvalidEntityException $exception) {
        $error = '<p class="alert alert-danger">'.$exception->getMessage().'</p>';
    }
}

$_PAGE_TITLE = 'Modifier '.$cycle->get('name');

$content = '
    <h2>Modifier <a href="/serie/'.$cycle->get('url').'">'.$cycle->get('name').'</a></h2>

    '.$error.'

    <form method="post" class="fieldset">
        <fieldset>
            <legend>Informations</legend>
            <label for="cycle_name">Nom :</label>
            <input type="text" name="cycle_name" id="cycle_name" value="'.$cycle->get('name').'" class="long">
            <br />
        </fieldset>
        <fieldset>
            <legend>Chap&ocirc;</legend>
            <textarea id="cycle_desc" name="cycle_desc" class="wysiwyg">'.$cycle->get('desc').'</textarea>
            <br />
        </fieldset>
        <fieldset>
            <div class="center">
              <input type="submit" class="btn btn-primary" value="Enregistrer les modifications">
            </div>
        </fieldset>
        <fieldset>
            <legend>Base de donn&eacute;es</legend>
            <label for="cycle_id">Cycle n&deg;</label>
            <input type="text" name="cycle_id" id="cycle_id" value="'.$cycle->get('id').'" readonly>
            <br>
            <label for="cycle_url">URL :</label>
            <input type="text" name="cycle_url" id="cycle_url" value="'.$cycle->get('url').'" class="long">
            <br><br>
            <label for="cycle_insert">Date de cr&eacute;ation :</label>
            <input type="datetime" name="cycle_insert" id="cycle_insert" value="'.$cycle->get('insert').'" class="datetime" readonly>
            <br>
            <label for="cycle_update">Date de modification :</label>
            <input type="datetime" name="cycle_update" id="cycle_update" value="'.$cycle->get('update').'" class="datetime" readonly>
            <br>
        </fieldset>
    </form>
';

return new Response($content);
