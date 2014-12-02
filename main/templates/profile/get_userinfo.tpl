{
    "id": {{ user.get_id() }},
    "fio": "{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}",
    "telephone": "{{ user.get_telephone() }}",
    "cellphone": "{{ user.get_cellphone() }}"
}