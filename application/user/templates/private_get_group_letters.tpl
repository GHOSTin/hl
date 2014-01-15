{% extends "ajax.tpl" %}
{% set letters = component.letters %}
{% block js %}
    $('.letters').html(get_hidden_content('._letters'));
    $('.get_user_letters').removeClass('active');
    $('.get_group_letters').addClass('active');
    $('.letter-content').html(get_hidden_content('._letter_content'));
{% endblock js %}
{% block html %}
    <div class="_letters">
    {% include '@user/build_group_letters.tpl' %}
    </div>
    <div class="_letter_content">
        <div>
            <a class="btn btn-link get_dialog_create_group">Создать группу</a>
        </div>
    </div>
{% endblock html %}