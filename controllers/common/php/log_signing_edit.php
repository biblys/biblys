<?php

use Biblys\Legacy\LegacyCodeHelper;

if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin() && !LegacyCodeHelper::getGlobalVisitor()->isPublisher()) trigger_error('Vous n\'avez pas le droit d\'accéder à cette page.', E_USER_ERROR);

    $buttons = '<button type="submit" form="signing" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Enregistrer</button>';
    
    $sm = new SigningManager();
    $pm = new PeopleManager();
    
    // Edit an existing signing
	if (isset($_GET['id']))
	{
		if ($s = $sm->get(array('signing_id' => $_GET['id'])))
		{
            if (LegacyCodeHelper::getGlobalVisitor()->isPublisher() && $s->get('publisher_id') != LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id') && !LegacyCodeHelper::getGlobalVisitor()->isAdmin()) trigger_error("Vous n'avez pas le droit de modifier cette dédicace");
            \Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Modifier la dédicace');
            $buttons .= ' <button type="submit" form="signing" formaction="?delete" class="btn btn-danger" formnovalidate data-confirm="Voulez-vous vraiment SUPPRIMER cette dédicace ?"><i class="fa fa-trash-o"></i> Supprimer</button>';
		}
		else trigger_error('Cette dédicace n\'existe pas.', E_USER_ERROR);
	}
	
	// Create a new signing
	elseif ($_SERVER['REQUEST_METHOD'] != 'POST')
	{	
		\Biblys\Legacy\LegacyCodeHelper::setGlobalPageTitle('Ajouter une dédicace');
        $s = new Signing(array());
        if (LegacyCodeHelper::getGlobalVisitor()->isAdmin()) trigger_error("Vous ne pouvez pas créer de dédicace en tant qu'administrateur, connectez-vous en tant qu'éditeur.");
	}
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
        
        if (isset($_GET['delete']))
        {
            if ($s = $sm->get(array('signing_id' => $_POST['signing_id'])))
            {
                $params['success'] = 'La dédicace a bien été supprimée.';
                
                try
                {
                    $sm->delete($s);
                    redirect('/pages/log_signings_admin', $params);
                } catch (Exception $ex) {
                    trigger_error($ex->getMessage());
                }
                
            }
            else trigger_error('Cette dédicace n\'existe pas.', E_USER_ERROR);
        }
		else
        {
            $params = array();
            
            // Get people from name
            if ($p = $pm->get(array('people_name' => $_POST['people'])))
            {
                $_POST['people_id'] = $p->get('id');
                unset($_POST['people']);
            }
            else
            {
                trigger_error("L'auteur ".$_POST['people']." n'existe pas dans la base. Veuillez au préalable créer un livre dans votre catalogue et y associer cet auteur.");
            }

            // Create new event
            if (empty($_POST['signing_id']))
            {
                $s = $sm->create();
                $_POST['signing_id'] = $s->get('id');
            }

            if ($s = $sm->get(array('signing_id' => $_POST['signing_id'])))
            {
                foreach ($_POST as $key => $val)
                {
                    $s->set($key, $val);
                }
                
                // Associate to current right
                if (!LegacyCodeHelper::getGlobalVisitor()->isAdmin())
                {
                    if (LegacyCodeHelper::getGlobalVisitor()->isPublisher()) $s->set('publisher_id', LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id'));
                }
                
                $s = $sm->update($s);
                
                $params['success'] = 'La dédicace a bien été mise à jour.';
                redirect('/pages/log_signings_admin', $params);
            }
            else trigger_error('Cette dédicace n\'existe pas.', E_USER_ERROR);
        }
		
	}
	else
	{	
        
        
        // Stand LAL
        if (LegacyCodeHelper::getLegacyCurrentSite()['site_id'] == 11 && LegacyCodeHelper::getGlobalVisitor()->isPublisher())
        {
            $sum = new SubscriptionManager();
            if ($su = $sum->get(array('site_id' => 11, 'publisher_id' => LegacyCodeHelper::getGlobalVisitor()->getCurrentRight()->get('publisher_id'))))
            {
                if ($su->has('subscription_type'))
                {
                    $s->set('location', $su->get('subscription_type'));
                }
            }
                    
        }
        
		$_ECHO .= '
			<h1><i class="fa fa-pencil"></i> '.\Biblys\Legacy\LegacyCodeHelper::getGlobalPageTitle().'</h1>
            <p>'.$buttons.'</p>

			<form id="signing" method="post" class="form-horizontal fieldset">
				<fieldset>
					<legend>Informations</legend>
					
					<p>
                        <label for="people_id">Auteur :</label>
                        <input type="text" id="people" name="people" value="'.($s->has('people') ? $s->get('people')->get('name') : null).'" class="verylong" required data-toggle="popover" data-trigger="focus" data-content="Entrer le nom complet de l\'auteur. L\'auteur doit au préalable exister dans la base, c\'est-à-dire être associé à au moins un livre de votre catalogue." data-original-title="Auteur invité">
                    </p>
                    
					<p>
                        <label for="signing_date">Date :</label>
                        <input type="date" id="signing_date" name="signing_date"  value="'.$s->get('date').'" placeholder="AAAA-MM-JJ" required>
                    </p>
                    
					<p>
                        <label for="signing_starts">Horaires :</label>
                        de <input type="time" id="signing_starts" name="signing_starts" value="'.$s->get('starts').'" placeholder="HH:MM:SS" required>
                        à <input type="time" id="signing_ends" name="signing_ends" value="'.$s->get('ends').'" placeholder="HH:MM:SS" required>
                    </p>
                    
					<p>
                        <label for="signing_location">Lieu :</label>
                        <input type="text" id="signing_location" name="signing_location" value="'.$s->get('location').'" class="verylong" required>
                    </p>
                    
				</fieldset>
                
                <fieldset class="center">
					'.$buttons.'
				</fieldset>
                <fieldset>
					<legend>Base de données</legend>
					<p>
						<label for="event_id"">Dédicace n&deg; :</label>
						<input type="text" name="signing_id" id="signing_id" value="'.$s->get('id').'" class="short" readonly>
					</p>
					<br>
					
					<p>
						<label>Fiche crée le :</label>
						<input type="text" value="'.($s->has('created') ? $s->get('created') : null).'" disabled class="long">
					</p>
					<p>
						<label>Fiche modifiée le :</label>
						<input type="text" value="'.($s->has('updated') ? $s->get('updated') : null).'" disabled class="long">
					</p>
                </fieldset>
			</form>
		
		';
	}