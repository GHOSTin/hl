{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set companies = component.companies %}
{% block js %}
    $('.user[user = {{ user.get_id() }}] .user-information').html(get_hidden_content());
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_add_profile">Добавить профиль</a>
    </div>
    {% if companies is not empty %}
        <ul class="unstyled user-profiles">
        {% for company in companies %}
            <li class="company" company="{{ company.get_id() }}">
                <div class="get_company_content">{{ company.get_name() }}</div>
            </li>
        {% endfor %}
        </ul>
    {% else %}
        Еще ни одного профиля не было создано.
    {% endif %}
{% endblock html %}