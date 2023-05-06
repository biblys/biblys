<?php

    use Biblys\Isbn\Isbn as Isbn;

    class Image
	{
		private $_i,
			$path;

		public function __construct(array $data)
		{
			$this->hydrate($data);
		}

		public function hydrate(array $data)
		{
			foreach ($data as $key => $val)
			{
				$this->set($key,$val);
			}
		}

		public function has($field)
		{
			return (bool) !empty($this->_i[$field]);
		}

		public function set($field, $value)
		{
			if ($field == "image_id") {
				$value = (int) $value;
			} elseif ($field == "image_ean" && !empty($value)) {
				$value = Isbn::convertToEan13($value);
			}

			$this->_i[$field] = $value;
		}

		public function get($field)
		{
			if (isset($this->_i[$field])) return $this->_i[$field];
			elseif (isset($this->_i['image_'.$field])) return $this->_i['image_'.$field];
			//else throw new Exception('Image: '.$field.' is not a valid field.');
		}

		/* Get all values in an array */
		public function getArray()
		{
			return $this->_i;
		}

		/* Get image dir and create it if needed */
		public function getDir()
		{
			$dir = biblysPath() . "/../../images/files" ."/".str_pad(substr($this->get('image_id'),-2,2),2,'0',STR_PAD_LEFT).'/'.$this->get('image_id').'/';
			// trigger_error($dir);
			if (!is_dir($dir)) {
				mkdir($dir, 0777, true);
			}
			return $dir;
		}

		/* Get image path */
		public function getPath()
		{
			return $this->getDir().'image'.$this->getType('ext');

		}

		/* Get image type */
		public function getType($data)
		{

			// Get current image mime-type
			$type = $this->get('image_type');

			// Alternate mime types
			switch ($type)
			{
				//case 'application/ogg': $type = 'audio/ogg'; break;
				//case 'audio/mpeg': $type = 'audio/mp3'; break;
			}

			// Get data according to type
			$types = array(
				'image/gif' => array(
					'name' => 'GIF',
					'ext' => '.gif',
					'mime' => 'image/gif'
				),
				'image/jpeg' => array(
					'name' => 'JPEG',
					'ext' => '.jpg',
					'mime' => 'image/jpeg'
				),
				'image/png' => array(
					'name' => 'PNG',
					'ext' => '.png',
					'mime' => 'image/png'
				)
			);

			if (isset($types[$type])) return $types[$type][$data];
			else throw new Exception('Unknown type: '.$type);

		}

		/* Get image download url */
		public function getURL()
		{
			return 'http://images.biblys.fr/files/'.str_pad(substr($this->get('image_id'),-2,2),2,'0',STR_PAD_LEFT).'/'.$this->get('id').'/image'.$this->getType('ext');
		}

		/* Create table line */
		public function getLine()
		{
			$sel[0] = NULL; $sel[1] = NULL;
			$sel[$this->get('image_access')] = ' selected';

			$natures = array(
				'cover' => 'Couverture',
				'illustration' => 'Illustration',
				'logo' => 'Logo',
				'photo' => 'Photo'
			);

			foreach ($natures as $k => $v) $nature_select[] = '<option value="'.$k.'"'.($this->get('nature') == $k ? ' selected' : null).'>'.$v.'</option>';

			$line = '
				<tr id="image_'.$this->get('id').'" class="image">
					<td class="center" style="width: 150px;"><img src="'.$this->getURL().'" style="max-width: 100%; max-height: 100%;"></td>
					<td class="center"><select class="image_nature">'.implode($nature_select).'</td>
					<td class="image_legend" contenteditable>'.$this->get('image_legend').'</td>
					<td>'.$this->getType('name').'</td>
					<td class="center">'.file_size($this->get('image_size')).'</td>
					<td class="center" style="width: 100px;">
						<img src="/common/icons/save.svg" data-update_image='.$this->get('id').' class="pointer event" width=16 alt="Enregistrer" title="Enregistrer les modifications">
						<img src="/common/icons/delete.svg" data-delete_image='.$this->get('id').' class="pointer event" width=16 alt="Supprimer" title="Supprimer le fichier">
					</td>
				</tr>
			';

			return $line;
		}

		/* Get human-readable name */
		public function getName()
		{
			// Get article url
			global $_SQL;
			$article = $_SQL->query('SELECT `article_url` FROM `articles` WHERE `article_id` = '.$this->get('article_id').' LIMIT 1');
			$a = $article->fetch(PDO::FETCH_ASSOC);

			return str_replace("/","_",$a["article_url"]).$this->getType('ext');
		}

		/* Get download count */
		public function getDownloads()
		{
			global $_SQL;
			$downloads = $_SQL->query('SELECT `download_id` FROM `downloads` WHERE `image_id` = '.$this->get('image_id'));
			$count = count($downloads->fetchAll(PDO::FETCH_ASSOC));
			return $count;
		}
    }

	class ImagesManager
	{
		private $_db;

		public function __construct(PDO $db)
		{
			$this->_db = $db;
		}

		/**
		 * Load Images manager
		 */

		public function manager($link_to, $link_to_id)
		{
			$images = $this->get(array($link_to.'_id' => $link_to_id)); // Get all files for this article
			$images_table = NULL;
			foreach($images as $i) $images_table .= $i->getLine();
			$manager = '
				<table id="images_list" class="admin-table imageUpload dropzone" data-link_to="'.$link_to.'" data-link_to_id="'.$link_to_id.'">
					<thead>
						<tr>
							<th>Image</th>
							<th>Nature</th>
							<th>Légende</th>
							<th>Type</th>
							<th>Poids</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						'.$images_table.'
					</tbody>
					<tfoot>
						<tr>
							<td class="center" colspan=6>
                                <p class="center">Faites glisser une image ici pour l\'importer.</p>
								<!--button type="button" data-alert="Faîtes glisser une image depuis le bureau sur le tableau pour l\'importer">Importer depuis l\'ordinateur</button>
								<button type="button" disabled>Importer depuis le web</button>
								<button type="button" disabled>Utiliser une image déjà importée</button//-->
							</td>
						</tr>
					</tfoot>
				</table>
			';
			return $manager;
		}

		/**
		 * Le fichier existe-t-il dans la base ?
		 */
		public function exists($where)
		{
			$q = EntityManager::buildSqlQuery($where);

			$query = $this->_db->prepare('
				SELECT `image_id`
				FROM `images`
				WHERE '.$q['where'].' LIMIT 1');
			$query->execute($q['params']) or error($query->errorInfo());

			if ($query->fetch(PDO::FETCH_ASSOC)) return true;
			return false;

		}

		/**
		 * Obtenir les informations sur le fichier
		 */
		public function get($where)
		{

			$q = EntityManager::buildSqlQuery($where);

			$query = $this->_db->prepare('
				SELECT *
				FROM `images`
				WHERE '.$q['where'].'
				GROUP BY `image_id`');
			$query->execute($q['params']) or error($query->errorInfo());

			$images = array();
			while ($f = $query->fetch(PDO::FETCH_ASSOC))
			{
				$images[] = new Image($f);
			}

			return $images;
		}


		/**
		 * Créer le fichier dans la base de données
		 */
		public function create()
		{
			global $_SITE;
			$insert = $this->_db->query('INSERT INTO `images`(`site_id`, `image_inserted`) VALUES('.$_SITE['site_id'].', NOW())');
			$id = $this->_db->lastInsertId();

			$get = $this->get(array('image_id' => $id));
			return $get[0];
		}

		/**
		 *  Update database from object
		 */
		public function update(Image $i)
		{

			global $_LOG, $_SITE;

			$fields = array(); // Updated fields array
			$params = array(); // SQL query params

			// Get old field to compare
			if ($olds = $this->get(array('image_id' => $i->get('id'))))
			{
				$o = $olds[0];
			}
			else throw new Exception('Image #'.$i->get('id').' not found');

			$iA = $i->getArray();

			// Build update query from modified fields
			foreach ($iA as $key => $val)
			{
				if ($o->get($key) != $val) // If field has beed modified
				{
					$fields[] = $key;

					if (!isset($query)) $query = '';
					else $query .= ', ';

					if (empty($val)) $query .= '`'.$key.'` = NULL';
					else
					{
						$query .= '`'.$key.'` = :'.$key;
						$params[$key] = $val;
					}

				}
			}

			// Update database
			if (isset($query))
			{

				try
				{
					$query = 'UPDATE `images` SET '.$query.', `image_updated` = NOW() WHERE `image_id` = '.$i->get('id');
					$sql = $this->_db->prepare($query);
					$sql->execute($params);
				}
				catch (PDOException $e)
				{
					throw new Exception($e.' <br> Query: '.$query.' <br> Params: '.print_r($params, 1));
				}
			}

			$images = $this->get(array('image_id' => $i->get('id')));
			return $images[0];

		}

		/**
		 * Uploader un nouveau fichier
		 */
		public function upload(Image $image, $path, $name, $link_to, $link_to_id)
		{

			// Get image data
			$name = explode('.', $name);
			$title = $name[0];
			$ext = $name[1];
			$type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
			$size = filesize($path);

			// Unknown mime types
			if ($type == 'application/octet-stream' && $ext = 'mobi') $type = 'application/x-mobipocket-ebook';

			// Update image data
			$image->set($link_to.'_id', $link_to_id);
			$image->set('image_legend', $title);
			$image->set('image_type', $type);
			$image->set('image_size', $size);
			$image->set('image_uploaded', date('Y-m-d H:i:s'));

			// Default image nature
			if (!$image->has('image_nature'))
			{
				if($link_to == 'article') $image->set('image_nature', 'cover');
				elseif($link_to == 'bookshop') $image->set('image_nature', 'logo');
				elseif($link_to == 'library') $image->set('image_nature', 'logo');
			}

			// Copy image from temp to directory
			if (copy($path, $image->getPath()))
			{
				$this->update($image);
			}
			else throw new Exception('Copy error');
		}

		/**
		 * Supprimer le fichier
		 * @param object $image Le fichier supprimé
		 */
		public function delete(Image $image)
		{
			try
			{
				$delete = $this->_db->query('DELETE FROM `images` WHERE `image_id` = '.$image->get('image_id').' LIMIT 1');
			}
			catch (PDOException $e)
			{
				throw new Exception($e);
			}
		}

	}
