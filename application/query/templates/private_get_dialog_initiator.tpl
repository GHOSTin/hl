<script>
	function _hidden_content(){
		$('.modal-body').html(get_hidden_content());
		$('.dialog-select-street').change(function(){
			$.get('get_houses',{
				id: $('.dialog-select-street :selected').val()
			},function(r){
				$('.dialog-select-house').html(r).attr('disabled', false);
			});
		});		
		{% if initiator == 'house' %}
			$('.dialog-select-house').change(function(){
				$.get('get_initiator',{
					initiator: 'house',
					id: $('.dialog-select-house :selected').val()
				},function(r){
					init_content(r);
				});
			});	
		{% else %}
			$('.dialog-select-house').change(function(){
				$.get('get_numbers',{
					id: $('.dialog-select-house :selected').val()
				},function(r){
					$('.dialog-select-number').html(r).attr('disabled', false);
				});
			});
			$('.dialog-select-number').change(function(){
				$.get('get_initiator',{
					initiator: 'number',
					id: $('.dialog-select-number :selected').val()
				},function(r){
					init_content(r);
				});
			});	
		{% endif %}
	}
</script>
<div class="_hidden_content_html">
{% if streets != false %}
	<select style="display:block" class="dialog-select-street">
		{% for street in streets %}
			<option value="{{street.id}}">{{street.name}}</option>
		{% endfor %}
	</select>
	<select class="dialog-select-house" style="display:block" disabled="disabled">
		<option value="0">Ожидание...</option>
	</select>
	{% if initiator == 'number' %}
		<select class="dialog-select-number" style="display:block" disabled="disabled">
			<option value="0">Ожидание...</option>
		</select>
	{% endif %}
{% endif %}
</div>