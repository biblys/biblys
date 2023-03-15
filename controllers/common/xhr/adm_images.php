<?php

	$r = array();
	
	require_once biblysPath().'/inc/Image.class.php';
	$_IMAGES = new ImagesManager($_SQL);

	if (isset($_POST['action']))
	{
		// If image exist
		if (isset($_POST['image_id']) && !strstr($_POST['image_id'], 'new_'))
		{
			
			// Check if image exists with this id in database
			if ($_IMAGES->exists(array('image_id' => $_POST['image_id'])))
			{
				
				$images = $_IMAGES->get(array('image_id' => $_POST['image_id']));
				$image = $images[0];
			}
			else
			{
				$r['error'] = 'Le fichier n\'existe pas !';
			}
		}
		elseif (strstr($_POST['image_id'], 'new_'))
		{
			$image = $_IMAGES->create();
		}
		else $r['error'] = 'Le fichier n\'existe pas !';
		
		
		// Delete image
		if ($_POST['action'] == 'delete')
		{	
			try
			{
				$_IMAGES->delete($image);
			}
			catch (Exception $e)
			{
				error($e);
			}
			
			$r['success'] = 'L\'image a bien été supprimée.';
		}
		
		// Update
		else if($_POST['action'] == 'update')
		{
			
			try
			{
				$image->set('image_legend', $_POST['image_legend']);
				$image->set('image_nature', $_POST['image_nature']);
				$_IMAGES->update($image);
			}
			catch (Exception $e)
			{
				json_error(0, $e->getMessage());
			}
			
			$r['success'] = 'L\'image a bien été mise à jour.';
			
		}
		
		// Upload a image from computer
		elseif ($_POST['action'] == 'upload' && isset($_FILES['image']))
		{
			
			$f = $_FILES['image'];
			
			// Copy image into the images directory
			try
			{
				$_IMAGES->upload($image, $f['tmp_name'], $f['name'], $_POST['link_to'], $_POST['link_to_id']);
			}
			catch (Exception $e)
			{
				error($e);
			}
			
			// Return new table line
			$r['success'] = 'L\'image &laquo;&nbsp;'.$f['name'].'&nbsp;&raquo; a bien été ajoutée.';
			$r['new_line'] = $image->getLine();
		}
		
	}
	
	echo json_encode($r);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

