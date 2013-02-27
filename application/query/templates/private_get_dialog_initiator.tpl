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
				$.get('index.php',{p: 'query.get_initiator',
					initiator: 'house',
					id: $('.dialog-select-house :selected').val()
				},function(r){
					$('.modal-body').html(r);
				});
			});	
		{% else %}
			$('.dialog-select-house').change(function(){
				$.get('index.php',{p: 'query.get_numbers',
					id: $('.dialog-select-house :selected').val()
				},function(r){
					$('.dialog-select-number').html(r).attr('disabled', false);
				});
			});
			$('.dialog-select-number').change(function(){
				$.get('index.php',{p: 'query.get_initiator',
					initiator: 'number',
					id: $('.dialog-select-number :selected').val()
				},function(r){
					$('.modal-body').html(r);
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
		<select class="dialog-select-number" style="display:block" disabled="disabled">
			<option value="0">Ожидание...</option>
		</select>
	{% endif %}
{% endif %}