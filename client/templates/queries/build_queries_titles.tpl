{% set status = {'open': 'Открытая', 'close': 'Закрытая', 'working': 'В работе', 'reopen': 'Открытая'} %}
{% set status_classes = {'open': 'label-primary', 'close': 'label-default', 'working': 'label-info', 'reopen': 'label-primary'} %}

{% for query in queries %}
  <tr>
    <td class="project-status">
      <span class="label {{ status_classes[query.get_status()] }}">{{ status[query.get_status()] }}</span>
    </td>
    <td class="project-title" data-value="{{ query.get_number() }}">
      {% if query.get_request() %}
        <h3 class="no-margins">Заявка №{{ query.get_number() }}</h3>
        <small>от {{ query.get_time_open()|date('d.m.Y') }} на основании запроса от {{ query.get_request().get_time()|date('d.m.Y') }}</small>
      {% else %}
        <h3 class="no-margins">Заявка №{{ query.get_number() }}</h3>
        <small>от {{ query.get_time_open()|date('d.m.Y') }}</small>
      {% endif %}
    </td>
    {% if query.get_request() %}
    <td class="project-query">
        {{ query.get_request().get_message() }}
    </td>
    {% else %}
      <td class="project-query"></td>
    {% endif %}
    <td class="project-description">
      {{ query.get_description() }}
    </td>
    <td class="project-creator">
      {{ query.get_creator().get_fio() }}
    </td>
    <td class="project-close_reason">
      {% if query.get_status() == 'close' %}
        {{ query.get_close_reason() }}
      {% endif %}
    </td>
  </tr>
{% endfor %}