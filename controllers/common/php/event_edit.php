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


use Biblys\Legacy\LegacyCodeHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


if (LegacyCodeHelper::getGlobalVisitor()->isAdmin() || LegacyCodeHelper::getGlobalVisitor()->isPublisher() || LegacyCodeHelper::getGlobalVisitor()->isBookshop() || LegacyCodeHelper::getGlobalVisitor()->isLibrary()) $mode = 'admin';
else trigger_error('Vous n\'avez pas le droit d\'accéder à cette page.', E_USER_ERROR);

$buttons = '<button type="submit" form="event" class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Enregistrer</button>';

$em = new EventManager();

$globalSite = LegacyCodeHelper::getGlobalSite();

$where = array('events`.`site_id' => $globalSite->get("id"));

if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin())
{
    if (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $where = array_merge($where, array('events`.`publisher_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id')));
    if (LegacyCodeHelper::getGlobalVisitor()->isBookshop()) $where = array_merge($where, array('events`.`bookshop_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('bookshop_id')));
    if (LegacyCodeHelper::getGlobalVisitor()->isLibrary()) $where = array_merge($where, array('events`.`library_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('library_id')));
}

// Edit an existing event
if (isset($_GET['id']))
{
    if ($e = $em->get(array('event_id' => $_GET['id'], 'site_id' => $globalSite->get("id"))))
    {
        $pageTitle = 'Modifier <a href="/evenements/'.$e['event_url'].'">'.$e['event_title'].'</a>';
        $buttons .= ' <button type="submit" form="event" formaction="?delete" class="btn btn-danger" formnovalidate data-confirm="Voulez-vous vraiment supprimer cet évènement ?"><i class="fa fa-trash-can"></i> Supprimer</button>';
    }
    else trigger_error('Cet évènement n\'existe pas.', E_USER_ERROR);
}

// Create a new event
else
{
    $pageTitle = 'Créer un nouvel évènement';
    $e = new Event(array());
}

/** @var Request $request */
$request->attributes->set("page_title", $pageTitle);

$content = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (isset($_GET['delete']))
    {
        if ($e = $em->get(array('event_id' => $_POST['event_id'], 'site_id' => $globalSite->get("id"))))
        {

            try
            {
                $em->delete($e);
                $success = $e->get('title').' a bien été supprimé.';
                return new RedirectResponse("/pages/log_events_admin?success=$success");
            } catch (Exception $ex) {
                trigger_error($ex->getMessage());
            }

        }
        else trigger_error('Cet évènement n\'existe pas.', E_USER_ERROR);
    }
    else
    {
        $params = array();

        // Create new event
        if (empty($_POST['event_id']))
        {
            $e = $em->create();
            $_POST['event_id'] = $e->get('id');
        }

        if ($e = $em->get(array('event_id' => $_POST['event_id'], 'site_id' => $globalSite->get("id"))))
        {
            foreach ($_POST as $key => $val)
            {
                $e->set($key, $val);
            }

            if ($e->has('start_date')) $e->set('event_start', $e->get('start_date').' '.$e->get('start_time'));
            if ($e->has('end_date')) $e->set('event_end', $e->get('end_date').' '.$e->get('end_time'));

            // Associate to current right
            if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin())
            {
                if (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $e->set('publisher_id', LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id'));
                if (LegacyCodeHelper::getGlobalVisitor()->isBookshop()) $e->set('bookshop_id', LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('bookshop_id'));
                if (LegacyCodeHelper::getGlobalVisitor()->isLibrary()) $e->set('library_id', LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('library_id'));
            }

            // URL
            $e->set('event_url', $em->makeslug($e));

            $e = $em->update($e);

            $success = $e->get('title').' a bien été mise à jour.';
            return new RedirectResponse("/evenements/".$e->get('url')."?success=$success");
        }
        else trigger_error('Cet évènement n\'existe pas.', E_USER_ERROR);
    }

}
else
{

    $content = '
        <h1><i class="fa fa-calendar"></i> '.$pageTitle.'</h1>
        <p>'.$buttons.'</p>

        <form id="event" method="post" class="fieldset">
            <fieldset>
                <legend>Informations</legend>
                
                <p>
                    <label for="event_title">Titre :</label>
                    <input type="text" name="event_title" id="event_title" value="'.($e->has('title') ? htmlspecialchars($e->get('title')) : null).'" class="verylong" required>
                </p>
                
                <p>
                    <label for="event_subtitle">Sous-titre :</label>
                    <input type="text" name="event_subtitle" id="event_subtitle" value="'.($e->has('subtitle') ? $e->get('subtitle') : null).'" class="verylong">
                </p>
                <br>
                
                <p>
                    <label for="event_start_date">Début :</label>
                    <input type="date" name="event_start_date" placeholder="AAAA-MM-DD" id="event_start_date" value="'.($e->has('start_date') ? $e->get('start_date') : null).'" required>
                    <input type="time" name="event_start_time" placeholder="HH:MM" id="event_start_time" value="'.($e->has('start_time') ? $e->get('start_time') : null).'" required>
                </p>
                
                <p>
                    <label for="event_end_date">Fin :</label>
                    <input type="date" name="event_end_date" placeholder="AAAA-MM-DD" id="event_end_date" value="'.($e->has('end_date') ? $e->get('end_date') : null).'">
                    <input type="time" name="event_end_time" placeholder="HH:MM" id="event_end_time" value="'.($e->has('end_time') ? $e->get('end_time') : null).'">
                </p>
                <br>
                
                <p>
                    <label for="event_location">Lieu :</label>
                    <textarea name="event_location" id="event_location" class="medium">'.($e->has('location') ? $e->get('location') : null).'</textarea>
                </p>
                
                
            </fieldset>
            
            <fieldset>
                <legend>Visibilité</legend>
                
                <p>
                    <label for="event_status">État :</label>
                    <select name="event_status">
                        <option value="0" '.($e->get('status') == 0 ? ' selected' : null).'>Hors-ligne</option>
                        <option value="1" '.($e->get('status') == 1 ? ' selected' : null).'>En ligne</option>
                    </select>
                </p>

                <p>
                    <label for="event_highlighted">Mise en avant :</label>
                    <input type="checkbox" name="event_highlighted" id="event_highlighted" value="1" '.($e->get('highlighted') == 1 ? ' checked' : null).' >
                </p>
            </fieldset>
            
            <fieldset>
            
                <legend>Description</legend>
                <textarea id="event_desc" name="event_desc" class="wysiwyg">'.($e->has('desc') ? $e->get('desc') : null).'</textarea>
            
            </fieldset>
            <fieldset class="center">
                <legend>Actions</legend>
                '.$buttons.'
            </fieldset>
            <fieldset>
                <legend>Base de données</legend>
                <p>
                    <label for="event_id"">Librairie n&deg; :</label>
                    <input type="text" name="event_id" id="event_id" value="'.$e->get('id').'" class="short" readonly>
                </p>
                <br>
                
                <p>
                    <label for="event_url">URL :</label>
                    <input type="text" name="event_url" id="event_url" value="'.($e->has('url') ? $e->get('url') : null).'" class="verylong" disabled>
                </p>
                
                <p>
                    <label>Fiche crée le :</label>
                    <input type="text" value="'.($e->has('created') ? $e->get('created') : null).'" disabled class="long">
                </p>
                <p>
                    <label>Fiche modifiée le :</label>
                    <input type="text" value="'.($e->has('updated') ? $e->get('updated') : null).'" disabled class="long">
                </p>
            </fieldset>
        </form>
    
    ';
}

return new Response($content);
