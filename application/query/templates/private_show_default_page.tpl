{% if queries != false %}
	{% for query in queries %}
		<div class="query get_query_content" query_id="{{query.id}}">
			<div>
				{% if query.initiator == 'number' %}
					<img src="templates/default/images/icons/xfn-friend.png" />
				{% else %}
					<img src="templates/default/images/icons/home-medium.png" />
				{% endif %}
				<b>№{{query.number}}</b>
			</div>
			{{query.description}}
		</div>
	{% endfor %}
{% endif %}