<script>
	function _content(){
		show_dialog($('.ajx').html());
		$('.get_dialog_initiator').click(function(){
			$.get('get_dialog_initiator',{
				value: $(this).attr('param')
				},function(r){
					show_content(r);
				});
		});
	}
</script>
<div class="ajx">
	<div class="modal">
	    <div class="modal-header">
	        <h3>Форма создания заявки</h3>
	    </div>	
		<div class="modal-body">
			Выберите: <span class="cm get_dialog_initiator" param="number">заявка на лицевой счет</span> или <span class="cm get_dialog_initiator" param="house">заявка на дом</span>
		</div>
		<div class="modal-footer">
			<div class="btn close_dialog">Отмена</div>
		</div>	  
	</div>
</div>