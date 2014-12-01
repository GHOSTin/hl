{% extends "ajax.tpl" %}

{% block js %}
    $('.letters').html(get_hidden_content('._users'))
    $('.get_group_letters').removeClass('active');
    $('.get_user_letters').addClass('active');
    $('.letter-content').html(get_hidden_content('._letter-content'));
{% endblock js %}

{% block html %}
    <div class="_users">
    {% include 'user/build_user_letters.tpl' %}
    </div>
    <div class="_letter-content">
        <div>
            <a class="btn btn-link get_dialog_create_user">Создать нового пользователя</a>
        </div>
    </div>
{% endblock html %}