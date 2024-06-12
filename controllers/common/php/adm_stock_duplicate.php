<?php

    $lm = new ListeManager();
    $sm = new StockManager();

    $_PAGE_TITLE = 'Dupliquer les exemplaires';

    if (!isset($_GET['list_id']))
    {
        $_ECHO .= '
            <h1>'.$_PAGE_TITLE.'</h1>
            <form>
                <label>Liste à dupliquer :</label>
                <input type="number" name="list_id" class="mini" required>
            </form>
        ';
    }
    else
    {
        $list = $lm->getById($_GET['list_id']);
        if ($list)
        {
            
            $stocks = $_SQL->prepare("SELECT `stock_id`, `stock`.`site_id` FROM `stock` JOIN `links` USING(`stock_id`) WHERE `list_id` = :list_id AND `stock_deleted` IS NULL");
            $stocks->bindParam('list_id', $_GET['list_id'], PDO::PARAM_INT);
            $stocks->execute();
            $stocks = $stocks->fetchAll(PDO::FETCH_ASSOC);
            
            if ($_SERVER['REQUEST_METHOD'] == "POST")
            {
                $duplicated = 0;
                foreach ($stocks as $s)
                {
                    $original = $sm->get(array('stock_id' => $s['stock_id'], 'site_id' => $s['site_id']));
                    
                    $copy = $sm->create();
                    
                    // Duplicate stock fields
                    $copy->set('article_id', $original->get('article_id'))
                        ->set('stock_invoice', $original->get('stock_invoice'))
                        ->set('stock_stockage', $original->get('stock_stockage'))
                        ->set('stock_condition', $original->get('stock_condition'))
                        ->set('stock_condition_details', $original->get('stock_condition_details'))
                        ->set('stock_purchase_price', $original->get('stock_purchase_price'))
                        ->set('stock_selling_price', $original->get('stock_selling_price'))
                        ->set('stock_weight', $original->get('stock_weight'))
                        ->set('stock_pub_year', $original->get('stock_pub_year'))
                        ->set('stock_purchase_date', $original->get('stock_purchase_date'))
                        ->set('stock_onsale_date', $original->get('stock_onsale_date'))
                    ;
                    
                    // Fields changed by form
                    if (!empty($_POST['site_id'])) 
                    {
                        $copy->set('site_id', $_POST['site_id']);
                    }
                    
                    if (!empty($_POST['stock_invoice'])) 
                    {
                        $copy->set('stock_invoice', $_POST['stock_invoice']);
                    }
                    
                    if (!empty($_POST['stock_stockage'])) 
                    {
                        $copy->set('stock_stockage', $_POST['stock_stockage']);
                    }
                    
                    if (!empty($_POST['stock_purchase_price'])) 
                    {
                        $copy->set('stock_purchase_price', $_POST['stock_purchase_price']);
                    }
                    
                    if (!empty($_POST['stock_purchase_date'])) 
                    {
                        $copy->set('stock_purchase_date', $_POST['stock_purchase_date']);
                    }
                    
        			// Copy picture
        			$orig_pic = new Media('stock', $original->get('id'));
                    $copy_pic = new Media('stock', $copy->get('id'));
        			if ($orig_pic->exists())
        			{
        				$copy_pic->upload($orig_pic->path());
        			}
                    
                    $sm->update($copy);
                    $duplicated++;
                    
                }
                
                redirect('/pages/adm_stock_duplicate', array('list_id' => $_GET['list_id'], 'duplicated' => $duplicated)); 
                
            }
            
            $_ECHO .= '
                <h1>'.$_PAGE_TITLE.'</h1>
                <h2>Liste : '.$list->get('title').' ('.count($stocks).' exemplaires)</h2>
            
                '.(isset($_GET['duplicated']) ? '<p class="success">'.$_GET['duplicated'].' exemplaires dupliqués</p>' : null).'
            
                <form action="/pages/adm_stock_duplicate?list_id='.$list->get('id').'" method="post" class="form-horizontal fieldset" role="form">
                    <fieldset>
                        <legend>Champs à modifier pour TOUS les exemplaires</legend>
                        
                        <div class="form-group">
                            <label for="site_id" class="col-sm-3 control-label">Site :</label>
                            <div class="col-sm-9">
                                <input id="site_id" name="site_id" type="text" class="form-control short" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock_invoice" class="col-sm-3 control-label">Lot / Facture :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="stock_invoice" name="stock_invoice">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock_stockage" class="col-sm-3 control-label">Emplacement :</label>
                            <div class="col-sm-9">
                                <input id="stock_stockage" name="stock_stockage" type="text" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock_purchase_price" class="col-sm-3 control-label">Prix d\'achat :</label>
                            <div class="col-sm-9">
                                <input id="stock_purchase_price" name="stock_purchase_price" type="number" class="short"> centimes
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock_purchase_date" class="col-sm-3 control-label">Mise en vente :</label>
                            <div class="col-sm-9">
                                <input id="stock_purchase_date" name="stock_purchase_date" type="date" class="form-control long">
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="text-center">
                        <button class="btn btn-primary" type="submit">Dupliquer</button>
                    </fieldset>
                    
                </form>
            ';
            
        }
        else
        {
            $_ECHO .= e404("Liste n°".$_GET['list_id']." inconnue.");
        }
    }
