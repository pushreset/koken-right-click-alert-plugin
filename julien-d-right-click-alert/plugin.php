<?php

class JulienDRightClickAlert extends KokenPlugin {

	function __construct()
	{
		$this->require_setup = true;
		$this->register_hook('before_closing_head', 'render');
	}

	function render()
	{

		$message 			= addslashes($this->data->message_text);
		$enable_dwld_link 	= $this->data->active_download_link == 1 ? 'true': 'false';
		$box_width			= $this->data->box_width;

		$base_selector = 'img.content, .k-content-embed img, .item img, #content img, #lane .cell img, .thumbs img, .mag img, .list-image img, #home-albums img, .img-wrap img, .pulse-main-container img';

		switch ( $this->data->active_custom_selector ) {
			
			case 1:
				$selector = $base_selector.', '.$this->data->custom_selector;
				break;
			case 2:
				$selector = $this->data->custom_selector;
				break;		
			case 0:
			default:
				$selector = $base_selector;
				break;
				
				break;
		}

		echo <<<OUT
<script type="text/javascript">
/* Right Click Alert Plugin */
(function() {
	var RCAP = {};

	RCAP.enableDwldLink = {$enable_dwld_link};

	RCAP.displayContextAlert = function displayContextAlert(event, element){
		RCAP.removeContextAlert();

		var boxSize 		= '{$box_width}';
		var clickPosition 	= event.clientX;
		var windowWidth 	= $(window).width();

		var xPosition = event.pageX - (boxSize/2);
		var yPosition = event.pageY;

		// detect if box hidden on horizontal themes
		if( (windowWidth - clickPosition) < (boxSize/2) ){
			xPosition = xPosition - (boxSize/2);
		}
		else if( clickPosition < (boxSize/2) ){
			xPosition = xPosition + (boxSize/2);
		}

		var partialTpl = '';

		if(RCAP.enableDwldLink){
			partialTpl = partialTpl + '<div class="rcap-dwld-link"><a href="'+element.attr('src')+'" target="_blank">Download</a></div>';
		}

		$('body').append('<div id="rcap-context" style="left:'+xPosition+'px;top:'+yPosition+'px;"><div class="rcap-message">{$message}</div>'+partialTpl+'</div>');

		RCAP.setTimer();
	};

	RCAP.removeContextAlert = function removeContextAlert(){
		RCAP.clearTimer();
		$('#rcap-context').remove();
	};

	RCAP.timer = null;

	RCAP.setTimer = function setTimer(){
		RCAP.clearTimer();
		RCAP.timer = setTimeout(RCAP.removeContextAlert, 3000);
	};

	RCAP.clearTimer = function clearTimer(){
		clearTimeout(RCAP.timer);
	};

	$(document).on("contextmenu", "{$selector}", function(e){
	  	RCAP.displayContextAlert(e, $(this));
	   	return false;
	});
	
}());
</script>
<style type="text/css">
	#rcap-context{
		position:absolute;
		border-radius:4px 4px 4px 4px;
		box-shadow:0 0 5px #222222;
		z-index: 4242;
		overflow: hidden;
		font-size:10px;
		color:#f5f6f8;
		width:{$box_width}px;
	}
	#rcap-context .rcap-message{
		padding:5px 10px;
		background-color:#f34642;
	}
	#rcap-context .rcap-dwld-link{
		border-top: solid 1px #d4d4d4;
		background-color:#1a232a;
		text-align:center;
		font-size:8px;
		padding:2px 10px;
		text-transform:uppercase;
	}

	#rcap-context a{
		color:#f9a90a;
	}
</style>

OUT;

	}
}