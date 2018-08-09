/** 
 * Class with collection of methods for managing file uploads and main page
 * 
 * @author Felipe Oliveira <felipe.wget@gmail.com>
 * @version 0.1
 * @copyright Copyright © 2018, Felipe Rodrigues Oliveira
 */
const upload = {

	/** 
	 * Render main upload button
	 */ 
	init: function(){
		var html = upload._htmlButton();
		upload.render( html );

		var boxButton = document.getElementById('box-button');
		boxButton.ondragover = function (event) {
			$('div[box-button] button').text('');
	        $('div[box-button] button').prepend('<i icon-upload class="icon-upload-to-cloud"></i>');
	        $('div[box-button] button').attr('drop', 'true');

			return false;
		};

		boxButton.ondragleave = function (event) {
			$('div[box-button] button').text('');
	        $('div[box-button] button').prepend('<p>Clique ou Arraste seu Arquivo .TAB</p>');
	        $('div[box-button] button').removeAttr('drop');

			return false;
		};

	},

	/** 
	 * Function edicated page rendering function
	 * @param String html
	 */ 
	render: function( html ){
		upload.resetBox();
		$('div[container-upload]').prepend( html );
	},

	/** 
	 * Select and render and arrow the input file
	 */
	selectFile: function(){
		$('input[type="file"]').remove();
		$('body').prepend('<input type="file" name="upload"/>');
		$('input[type="file"]').click();
	},

	/** 
	 * Start Upload
	 */
	startUpload: function(){

		$.post( window.location.href + 'add-token', function( response ){
			var response = JSON.parse( response );
			if( response.cod == 200 ){


				var html = upload._htmlProgress();
				upload.render( html );


					var formData = new FormData();

					if( $('input[type="file"]').val() != "" && $('input[type="file"]').val() != undefined && $('input[type="file"]').val() != null ){
						formData.append('upload', $('input[type="file"]')[0].files[0]);
					}

					if( $('input[type="hidden"]') && $('input[type="hidden"]').val() != "" && $('input[type="hidden"]').val() != null && $('input[type="hidden"]').val() != undefined ){
						formData.append('base64_file', $('input[type="hidden"]').val());
					}

					upload.addTokenInList( response.token );

					var xhr = new XMLHttpRequest();
					xhr.open('post', window.location.href + response.token + '/record', true);

					xhr.upload.onprogress = function(e) {

						$('input[type="file"]').remove();
					    var percentage = (e.loaded / e.total) * 100;

						$('div[progress] div[progressbar] div[percentage]').css('width', Math.ceil(percentage) + "%" );
						$('div[progress] p[text-percent]').text( Math.ceil(percentage) + "%" );

					};

					xhr.onerror = function(e) {
					  upload.errorUpload( 'Ocorreu um erro ao fazer o upload' );
					};
					xhr.onload = function() {

						var jsonResponse;
						try {
						   jsonResponse = JSON.parse(xhr.response);
						}
						catch (e) {
						   upload.errorUpload( 'Erro ao configurar o MVC: ' + xhr.response );
						}

						if( jsonResponse.cod == 200 ){
							upload.successUpload( jsonResponse.gross_revenue );
						} else {
							var message = "";
							if( jsonResponse.message ){
								upload.errorUpload('Erro ao fazer o upload: ' + jsonResponse.message );
							} else {
								upload.errorUpload('Erro no Upload' );
							}
						}

					};

					xhr.send(formData);

				}
			
		});

	},

	/** 
	 * Render error upload
	 * @param String errorMessage
	 */
	errorUpload: function( errorMessage ){
		var html = upload._htmlError( errorMessage );
		upload.render( html );
	},

	/** 
	 * Render success upload
	 * @param String grossRevenue
	 */
	successUpload: function( grossRevenue ){
		var html = upload._htmlSuccess( grossRevenue );
		upload.render( html );

		$('div[content-tab] div[item-icon-token] i[icon-arrow]').attr('class', 'icon-arrow-left4');
		$('body div[content-list-tokens]').attr('active', 'true');
	
		setTimeout(function(){
			$('div[content-tab] div[item-icon-token] i[icon-arrow]').attr('class', 'icon-arrow-right4');
			$('body div[content-list-tokens]').removeAttr('active');
		}, 800);
		
	},

	/** 
	 * Clear renders on page
	 */
	resetBox: function(){
		$('div[box-success], div[box-error], div[progress], div[box-button]').remove();
	},

	/** 
	 * Render main upload button
	 */ 
	resetUpload: function(){
		upload.init();
	},

	/** 
	 * Check the file input
	 * @param String pathArchive
	 */ 
	validateUpload: function( pathArchive ){
		if( pathArchive && pathArchive != undefined && pathArchive !== null ){
			var extension = pathArchive.split('.').reverse()[0];
			if( extension.toLowerCase() == 'tab' ){
				// Verificar a tabulaçao também mas por ultimo, antes quero verificar no PHP
				upload.startUpload();
			} else {
				upload.errorUpload('É aceito apenas arquivos com a extensão: .tab');
			}
		}
	},

	/** 
	 * Process Drop Upload
	 * @param Event
	 */
	processDrop: function( event ){

		event.preventDefault && event.preventDefault();
		this.className = '';
		var file = event.dataTransfer.files[0];

		console.log( file.name );
		var reader = new FileReader();
		reader.onload = function(event) {
            console.log( event.target.result );

            $('input[type="hidden"]').remove();
			$('body').prepend('<input type="hidden" name="base64_file" value="' + event.target.result + '"/>');

			upload.startUpload();

        }
        reader.readAsDataURL( file );

        return false;

	},

	/** 
	 * HTML code for "UPLOAD SUCCESS"
	 * @param Int grossRevenue
	 */
	_htmlSuccess: function( grossRevenue ){
		var html = '';
		html += '<div box-success>';
			html += '<i class="icon-check2"></i>';
			html += '<p text>Arquivo registrado com sucesso! veja na aba ao lado os "Token do .TAB" para mais detalhes</p>';
			html += '<div>';
				html += '<label>Receita Bruta:</label>';
				html += '<span>' + grossRevenue + '</span>';

				html += '<button>';
					html += '<p>Enviar outro Arquivo</p>';
				html += '</button>';
			html += '</div>';
		html += '</div>';
		return html;
	},

	/** 
	 * HTML code for "UPLOAD ERROR"
	 * @param String errorMessage
	 */
	_htmlError: function( errorMessage ){
		var html = '';
		html += '<div box-error>';
			html += '<i class="icon-cross2"></i>';
				html += '<p text>' + errorMessage + '</p>';
			html += '<div>';
				html += '<button>';
					html += '<p>Entendi</p>';
				html += '</button>';
			html += '</div>';
		html += '</div>';
		return html;
	},

	/** 
	 * HTML code for "UPLOAD IN PROGRESS"
	 */
	_htmlProgress: function(){
		var html = '';
		html += '<div progress>';
			html += '<div progressbar>';
				html += '<div percentage></div>';
			html += '</div>';
			html += '<p text-percent>0%</p>';
		html += '</div>';
		return html;
	},

	/** 
	 * HTML code for "UPLOAD INIT BUTTON"
	 */
	_htmlButton: function(){
		var html = '';
		html += '<div box-button id="box-button">';
			html += '<button>';
				html += '<p>Clique ou Arraste seu Arquivo .TAB</p>';
			html += '</button>';
		html += '</div>';

		return html;
	},

	/** 
	 * HTML code for "UPLOAD INIT BUTTON DROP"
	 */
	_htmlBoxDropUpload: function(){
		var html = '';
		html += '<div box-drop id="drop">';
			html += '<div border-dotted>';
				html += '<i icon-upload class="icon-upload-to-cloud"></i>';
				html += '<p>Arraste seu aquivo para cá</p>';
			html += '</div>';
		html += '</div>';

		return html;
	},

	/** 
	 * HTML code for "TOKEN BLOCK" in list
	 * @param String token
	 */
	_htmlToken: function( token ){
		var html = '';
		html += '<a href="./' + token + '">';
			html += '<div content-token="">';
				html += '<div name-token="">' + token + '</div>';
				html += '<i class="icon-angle-double-right"></i>';
			html += '</div>';
		html += '</a>';

		return html;
	},

	/** 
	 * Define height to list tokens
	 */
	defineSimpleScrollToken: function(){
		var list = $('div[content-list]');
		list.css('height', ( $(window).height() - 30 ) );
		new SimpleBar( document.querySelector('div[content-list]') );
	},

	/** 
	 * Prepend token on list token
	 * @param String token
	 */
	addTokenInList: function( token ){

		var exist_in_list = 0;
		$('div[content-list] div[content-token] div[name-token]').each(function(){
			if( $(this).text() == token ){
				exist_in_list = 1;
			}
		});

		if( exist_in_list == 0 ){
			var html = upload._htmlToken( token );
			$('div[content-list] .simplebar-content').prepend( html );
		}
	}

}

$(document).ready(function(){

	upload.init();

	$('body').delegate('input[type="file"]', 'change', function(){
		upload.validateUpload( $(this).val() );
	});

	$('body').delegate('div[box-button] button', 'click', function(){
		upload.selectFile();
	});

	$('body').delegate('div[box-error] button', 'click', function(){
		upload.init();
	});

	$('body').delegate('div[box-success] button', 'click', function(){
		upload.init();
	});


	var boxButton = document.getElementById('box-button');
	boxButton.ondragover = function (event) {
		$('div[box-button] button').text('');
        $('div[box-button] button').prepend('<i icon-upload class="icon-upload-to-cloud"></i>');
        $('div[box-button] button').attr('drop', 'true');

		return false;
	};

	boxButton.ondragleave = function (event) {
		$('div[box-button] button').text('');
        $('div[box-button] button').prepend('<p>Clique ou Arraste seu Arquivo .TAB</p>');
        $('div[box-button] button').removeAttr('drop');

		return false;
	};

	$('body div[content-list-tokens]').hover( function(){
		$('div[content-tab] div[item-icon-token] i[icon-arrow]').attr('class', 'icon-arrow-left4');
		$(this).attr('active', 'true');
	}, function(){
		$('div[content-tab] div[item-icon-token] i[icon-arrow]').attr('class', 'icon-arrow-right4');
		$(this).removeAttr('active');
	});

	upload.defineSimpleScrollToken();

});