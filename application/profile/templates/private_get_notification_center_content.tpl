{% if ans != false %}
<div class='user-list'>
	<div class='scroller'>
		<div class='scroller_conteiner'>
			<ul class='users'>
				{% for user in ans %}
					<li user_id="{{user.id}}">{{user.lastname}}</li>
				{% endfor %}
			</ul>
		</div>
        <div class='scroller__bar scroller__bar_state_on'></div>
    </div>
</div>
<div class='tab-content chat-list' id='main'></div>
{% endif %}
