<?php

    class Download extends Entity
	{
		protected $prefix = 'download';
    }
    
    class DownloadManager extends EntityManager
	{
		protected $prefix = 'download',
				  $table = 'downloads',
				  $object = 'Download';
		
	}
