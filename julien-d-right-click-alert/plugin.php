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
		$enable_dwld_link 	= $this->data->active_download_link == 1 ? 'true': 'false';;

		echo <<<OUT
<script type="text/javascript">
/* Right Click Alert Plugin */
(function() {
	var RCAP = {};

	RCAP.enableDwldLink = {$enable_dwld_link};

	RCAP.displayContextAlert = function displayContextAlert(event, element){
		RCAP.removeContextAlert();

		var partialTpl = '';

		if(RCAP.enableDwldLink){
			partialTpl = partialTpl + '<div class="rcap-dwld-link"><a href="'+element.attr('src')+'" target="_blank">Download</a></div>';
		}

		$('body').append('<div id="rcap-context" style="left:'+event.pageX+'px;top:'+event.pageY+'px;"><div class="rcap-message">{$message}</div>'+partialTpl+'</div>');

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

	$(document).on("contextmenu", "img.content, .k-content-embed img", function(e){
	  	RCAP.displayContextAlert(e, $(this));
	   	return false;
	});
	
}());
</script>
<style type="text/css">
	#rcap-context{
		position:absolute;
		background-color:#ffffff;
		color:#000000;
		border-radius:2px 2px 2px 2px;
		padding:5px 10px;
		box-shadow:0 0 5px #222222;
		z-index: 4242;
	}

	#rcap-context .rcap-dwld-link{
		text-align:center;
		font-size:10px;
		margin:5px 0 0 0;
		text-transform:uppercase;
	}
</style>

OUT;

	}
}