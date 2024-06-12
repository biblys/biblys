	// Charger la liste de fichier
	function loadFiles() {
		var open = $('#file_name').html();
		var path = $('#path').html();
		$('#loading').css('visibility','visible');
		$('aside ul').load('/x/log_files_load?user_uid='+user_uid+'&path='+path+'&open='+open, function(data) {
			reloadEvents(this);
			$('#loading').css('visibility','hidden');
		});
	}
	loadFiles();