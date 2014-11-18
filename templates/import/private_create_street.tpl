{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
    Улица "{{ response.street.get_name()}}" успешно залита.
{% endblock html %}