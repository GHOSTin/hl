{% if user.check_access('queries/analize_request') and number_requests is not empty %}
<span class="label label-info">{{ number_requests|length }} запросов из личного кабинета. <a class="get_requests">Подробнее...</a></span>
{% endif %}