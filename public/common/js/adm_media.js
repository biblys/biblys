/**
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