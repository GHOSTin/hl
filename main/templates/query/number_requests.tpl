{% if user.check_access('queries/analize_request') and number_requests is not empty %}
<div class="well">
  <h4>Запросы из личного кабинета</h4>
  <ul>
  {% for request in number_requests %}
    <li class="request" time="{{ request.get_time() }}" number="{{ request.get_number().get_id() }}">
    <span>{{ request.get_message() }}</span><a class="get_dialog_create_query_from_request"> cоздать заявку</a> или <a class="get_dialog_abort_query_from_request">отвергнуть запрос</a>
    </li>
  {% endfor %}
  </ul>
</div>
{% endif %}