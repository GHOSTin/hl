<script>
	function _content(){
		$('.modal-body').html(get_hidden_content());
		$('.dialog-select-street').change(function(){
			$.get('index.php',{p: 'query.get_houses',
				id: $('.dialog-select-street :selected').val()
			},function(r){
				$('.dialog-select-house').html(r).attr('disabled', false);
			});
		});		
		{% if initiator == 'house' %}
			$('.dialog-select-house').change(function(){
				$.get('index.php',{p: 'query.get_house_initiator',
					id: $('.dialog-select-house :selected').val()
				},function(r){
					//$('.dialog-select-house').html(r).attr('disabled', false);
				});
			});	
		{% endif %}
	}
</script>
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
		<select style="display:block" disabled="disabled">
			<option value="0">Ожидание...</option>
		</select>
	{% endif %}
{% endif %}