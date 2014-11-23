{% extends "default.tpl" %}

{% set number = response.number %}

{% block component %}
  <div class="row">
    <div class="col-md-6">
      <ul>
      {% for query in number.get_queries() %}
        {% if query.get_initiator() == 'number' %}
        <li>
          <h4>Контактная информация из заявки №{{ query.get_number() }}</h4>
          <ul class="list-unstyled">
            <li>ФИО: {{ query.get_contact_fio() }}</li>
            <li>Телефон: {{ query.get_contact_telephone() }}</li>
            <li>Сотовый телефон: {{ query.get_contact_cellphone() }}</li>
          </ul>
        </li>
        {% endif %}
      {% endfor %}
      </ul>
    </div>
  </div>
{% endblock %}