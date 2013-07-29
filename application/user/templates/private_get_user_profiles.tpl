{% extends "ajax.tpl" %}
{% set user = component.users[0] %}
{% set companies = component.companies %}
{% block js %}
    $('.user[user = {{user.id}}] .user-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% if companies is not empty %}
        <ul>
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