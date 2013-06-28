<ul class="unstyled">
{% set lrs = ['a', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'c', 'т', 'у', 'ф', 'x', 'ц', 'ч', 'ш', 'щ', 'э', 'ю', 'я'] %}
{% for letter in lrs %}
    <li title="{{ letters[letter] }} пользователей"><div class="get_user_letter{% if letters[letter]|length > 0 %} letter-noempty{% endif %}">{{ letter }}</div></li>
{% endfor %}
</ul>