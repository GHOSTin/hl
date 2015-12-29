{% set start = user.telephone|length - 4 %}
<div class="row">
    <div class="col-md-10">
        <div class="contact-box">
            <div class="profile-image">
                <img src="/images/profile_small.png" class="img-circle circle-border m-b-md" alt="Профиль">
            </div>
            <div class="profile-info">
                <div class="">
                    <div>
                        <h2 class="m-t-none">
                            {{ user.fio }}
                        </h2>
                        <div>
                            Телефон:
                            {% if start > 0 %}
                                {{ user.telephone|slice(0, start) }}-{{ user.telephone|slice(start,2) }}-{{ user.telephone|slice(start+2,2) }}
                            {% else %}
                                {{ user.telephone }}
                            {% endif %}
                            <span class="btn btn-link btn-xs get_dialog_edit_telephone"><i class="fa fa-edit"></i></span>
                        </div>
                        <div>
                            Сотовый: ({{ user.cellphone|slice(0,3) }}) {{ user.cellphone|slice(3,3) }}-{{ user.cellphone|slice(6,2) }}-{{ user.cellphone|slice(8,2) }} <span class="btn btn-link btn-xs get_dialog_edit_cellphone"><i class="fa fa-edit"></i></span>
                        </div>
                        <div>
                            Пароль: ****** <span class="btn btn-link btn-xs get_dialog_edit_password"><i class="fa fa-edit"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
