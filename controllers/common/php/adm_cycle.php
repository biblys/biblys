<?php

$cm = new CycleManager();
$am = new ArticleManager();

if ($cycle = $cm->get(array('cycle_id' => filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT))))
{

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $update_children = 0;
        foreach ($_POST as $key => $val)
        {
            $cycle->set($key, $val);
            if ($key == 'cycle_name') $update_children = 1;
        }

        // Update URL
        $cycle->set('cycle_url', $cm->makeslug($cycle));

        // Persist
        $cm->update($cycle);

        // Update children
        if ($update_children)
        {
            $articles = $am->getAll(array('cycle_id' => $cycle->get('id')));
            $params['articles_updated'] = 0;
            foreach ($articles as $article)
            {
                $article->set('article_cycle', $cycle->get('name'));
                $am->update($article);
                $params['articles_updated']++;
            }
        }

        $params['success'] = $cycle->get('name').' a bien été mise à jour.';
        redirect('/serie/'.$cycle->get('url'), $params);
    }

    $_PAGE_TITLE = 'Modifier '.$cycle->get('name');

    $_ECHO .= '
        <h2>Modifier <a href="/serie/'.$cycle->get('url').'">'.$cycle->get('name').'</a></h2>
            
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
}