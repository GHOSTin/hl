{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set companies = component.companies %}
{% block js %}
    $('.user[user = {{user.id}}] .user-information').html(get_hidden_content());
{% endblock js %}
{% block html %}
    <div>
        <a class="btn btn-link get_dialog_add_profile">Добавить профиль</a>
    </div>
    {% if companies is not empty %}
        <ul class="list-unstyled user-profiles">
        {% for company in companies %}
            <li class="company" company="{{ company.id }}">
                <div class="get_company_content">{{ company.name }}</div>
            </li>
        {% endfor %}
        </ul>
    {% else %}
        Еще ни одного профиля не было создано.
    {% endif %}
{% endblock html %}