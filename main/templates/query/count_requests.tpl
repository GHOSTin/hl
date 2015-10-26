{% if user.check_access('queries/analize_request') and number_requests is not empty %}
<span class="label label-info">Запросов из личного кабинета: {{ number_requests|length }} <a class="get_requests">подробнее...</a></span>
{% endif %}