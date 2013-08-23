{% extends "default.tpl" %}
{% set user = component.user %}
{% block component %}
	<div class="profile">
		<div class="profile-content">
			{% include '@profile/build_user_info.tpl' %}
		</div>
	</div>
{% endblock component %}
