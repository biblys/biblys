	// Redimensionner l'editeur
	function resizeEditor() {
		var window_height = $(window).height();
		var files_height = window_height - 100;
		var code_height = window_height - 185;
		$("#editor aside").css('height',files_height);
		$(".CodeMirror-scroll").css('height',code_height);
	}
	
	// Charger la liste de fichier
	function loadFiles() {
		//var open = $('#file_name').html();
		//var path = $('#path').html();
		$("#loading").css('visibility','visible');
		$("#editor aside").css('opacity','0.75');
		$.get('/x/adm_editor', {
			load: 1
		}, function(data) {
			var res = jQuery.parseJSON(data);
			$("#htmlFiles").html(res.html);
			$("#cssFiles").html(res.css);
			$("#imgFiles").html(res.img);
			$('#loading').css('visibility','hidden');
			$("#editor aside").css('opacity','1');
			reloadEditorEvents();
		});
	}
	
	// Charger l'editeur
	function loadEditor(id,type) {
		return CodeMirror.fromTextArea(document.getElementById(id), {
			mode: ""+type+"",
			tabMode: "indent",
			indentWithTabs: true,
			lineNumbers: true,
			indentUnit: 4,
			autofocus: true,
			onChange: function() { $(".selected").addClass("unsaved"); }
		});
	}
	
	// Sauvegarder tous les fichiers ouverts
	function saveFile(id) {
		var tab = $("#tab_"+id);
		tab.addClass("loading");
		$("#"+id).val(editor.getValue()); // sauvegarder editeur en cours
		$.post('/x/adm_editor?save=1', {
			id: ""+id+"",
			url: ""+tab.data("url")+"",
			content: ""+$("#"+id).val()+""
		}, function(data) {
			res = jQuery.parseJSON(data);
			tab.removeClass("loading")
			if(res.ok) tab.removeClass("unsaved");
			else if(res.error) alert(res.error);
			else alert(data);
		});
	}
	
	function reloadEditorEvents(scope) {
		
		// Ouvrir un fichier
		$(".openFile", scope).click( function() {
			var file = $(this);
			$(".selected").removeClass("selected");
			if(typeof editor !== 'undefined') { // S'il y a deja un fichier affiche, on le masque
				if(editor.hasOwnProperty("save")) {
					editor.save();
					$(".CodeMirror").remove();
				}
			}
			if($("#"+file.data("id")).length) { // Si le fichier est deja charge, on bascule
				$("#tab_"+file.data("id")).addClass("selected");
				editor = loadEditor(file.data("id"),file.data("type"));
				resizeEditor();
			} else { // Sinon, on charge le fichier
				overlay("show");
				$("#loading").css('visibility','visible');
				$.get('/x/adm_editor', {
					open: 1,
					file: ""+file.data("url")+""
				}, function(data) {
					var res = jQuery.parseJSON(data);
					$("#loading").css('visibility','hidden');
					overlay("hide");
					if(res.error) _alert(res.error);
					else {
						$("#tabs").append('<span id="tab_'+res.id+'" data-id="'+res.id+'" data-url="'+file.data("url")+'" data-type="'+file.data("type")+'" class="openFile selected">'+file.html()+'</span> ');
						$("#editor section").append('<textarea id="'+res.id+'">'+res.content+'</textarea>');
						editor = loadEditor(res.id,file.data("type"));
						reloadEditorEvents();
						resizeEditor();
						$("#editor nav").show();
					}
				});
			}
		});
		$(".openFile").removeClass("openFile");
		
		// Ouvrir une image
		$(".openImage").click( function() {
			var img = '<div class="center">';
			img += '<img src="'+$(this).data('url')+'" height="150">';
			img += '<p>Adresse : '+$(this).data('url')+'</p>';
			img += '</div>';
			_alert(img);
		});
		$(".openImage").removeClass("openImage");
		
		// Nouveau modele HTML
		$(".newHTML").click( function() {
			if(new_file_name = prompt("Nom du nouveau modèle :", "nouveau.html")) {
				$.get('/x/adm_editor', {
					create: 1,
					name: new_file_name
				}, function(data) {
					loadFiles();
				});
			}
		});
		$(".newHTML").removeClass("newHTML");
		
	}
	
	$(document).ready(function() {
		
		// Ouverture de l'editeur au chargement de la page
		$("#editor").dialog({
			autoOpen: true,
			height: ""+$(window).height()-15+"",
			title: "&Eacute;diteur HTML / CSS",
			width: ""+$(window).width()-15+"",
			stack: false,
			open: function() {
				resizeEditor();
				loadFiles();
			}
		});
		
		// Fermer un fichier
		$(".closeFile").click( function() {
			file_id = $(".selected").data("id");
			cconfirm = true;
			if($('.selected').hasClass('unsaved')) { var cconfirm = confirm("Voulez-vous vraiment quitter sans sauvegarder ?"); }
			if(cconfirm) {
				$(".selected").remove();
				$(".CodeMirror").remove();
				$("#"+file_id).remove();
				if($("#tabs span").length == 0) $("#editor nav").hide();
			}
		});
		
		// Bouton sauvegarder
		$(".saveFile").click( function() {
			saveFile($(".selected").data('id'));
		});
		
		// Autoformat
		$(".autoFormat").click( function() {
			var from = editor.getCursor(true);
			var to = editor.getCursor(false);
	        editor.autoFormatRange(from,to);
		});
		
		// Ouvrir le dialog Balises Biblys
		$(".showBiblysTags").click( function() {
			$("#biblysTags").dialog({
				title: 'Balises Biblys',
				width: 750
			});
		});
		
		// Ouvrir le dialog d'upload
		$('.openUploadDialog').click( function() {
			$("#uploadDialog").dialog({
				title: 'Uploader une image',
				width: 500
			});
		});
		
		// Upload de fichier
		$('#uploadFile').fileupload({
			dataType: 'json',
			dropZone: $('.dropzone'),
			start: function(e, data) {
				$("#uploadFile").hide();
				$('#uploading').show();
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#uploadProgress').attr('value',progress);
			},
			always: function (e, data) {
				$("#uploadDialog").dialog('close');
				$("#uploadProgress").attr('value','0');
				$("#uploadFile").show();
				$('#uploading').hide();
				loadFiles();
			}
		});
		
		$(document).bind('drop dragover', function (e) {
			e.preventDefault();
		});
		
		// Inserer un tag biblys
		$("#biblysTags .tag").click( function(e) {
			editor.replaceSelection($(this).html(), "end");
		});
		
		
	});