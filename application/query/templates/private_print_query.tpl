{%set query = component.queries[0] %}
{% if query.initiator == 'number' %}
	{% if component.numbers.numbers != false %}
		{% set number = component.numbers.numbers[component.numbers.structure[query.id].true[0]] %}
	{% endif %}
{% endif %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Печатная форма заявки №{{query.number}} от {{query.time_open|date('H:i d.m.Y')}}</title>
			<style>
				body {font-size:10pt; margin: 0mm 0mm 0mm 0mm; padding: 0mm 0mm 0mm 0mm;}
				.main-block table {border-collapse: collapse; border-spacing: 0mm;}
				.ttle {font-size:14pt; font-weight:900;}
				.main {border:1px solid grey; width:200mm; padding: 0mm 0mm 5mm 0mm;}
                .navbar {
                    *position: relative;
                    *z-index: 2;
                    margin-bottom: 20px;
                    overflow: visible;
                }
                .navbar-inverse .navbar-inner {
                    background-color: #1b1b1b;
                    background-image: -moz-linear-gradient(top, #222222, #111111);
                    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#222222), to(#111111));
                    background-image: -webkit-linear-gradient(top, #222222, #111111);
                    background-image: -o-linear-gradient(top, #222222, #111111);
                    background-image: linear-gradient(to bottom, #222222, #111111);
                    background-repeat: repeat-x;
                    border-color: #252525;
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff222222', endColorstr='#ff111111', GradientType=0);
                }
                .navbar-inner {
                    min-height: 40px;
                    padding-right: 20px;
                    padding-left: 20px;
                    background-color: #fafafa;
                    background-image: -moz-linear-gradient(top, #ffffff, #f2f2f2);
                    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#f2f2f2));
                    background-image: -webkit-linear-gradient(top, #ffffff, #f2f2f2);
                    background-image: -o-linear-gradient(top, #ffffff, #f2f2f2);
                    background-image: linear-gradient(to bottom, #ffffff, #f2f2f2);
                    background-repeat: repeat-x;
                    border: 1px solid #d4d4d4;
                    -webkit-border-radius: 4px;
                    -moz-border-radius: 4px;
                    border-radius: 4px;
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#fff2f2f2', GradientType=0);
                    *zoom: 1;
                    -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
                    -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
                    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.065);
                }
                .navbar-inner:before,
                .navbar-inner:after {
                    display: table;
                    line-height: 0;
                    content: "";
                }
                .navbar-inner:after {
                    clear: both;
                }
                .navbar .btn {
                    margin-top: 5px;
                }
                .btn {
                    display: inline-block;
                    *display: inline;
                    padding: 4px 12px;
                    margin-bottom: 0;
                    *margin-left: .3em;
                    font-size: 14px;
                    line-height: 20px;
                    color: #333333;
                    text-align: center;
                    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
                    vertical-align: middle;
                    cursor: pointer;
                    background-color: #f5f5f5;
                    *background-color: #e6e6e6;
                    background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
                    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
                    background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
                    background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
                    background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
                    background-repeat: repeat-x;
                    border: 1px solid #cccccc;
                    *border: 0;
                    border-color: #e6e6e6 #e6e6e6 #bfbfbf;
                    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
                    border-bottom-color: #b3b3b3;
                    -webkit-border-radius: 4px;
                    -moz-border-radius: 4px;
                    border-radius: 4px;
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
                    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
                    *zoom: 1;
                    -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
                    -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
                    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
                }

                .btn:hover,
                .btn:focus,
                .btn:active,
                .btn[disabled] {
                    color: #333333;
                    background-color: #e6e6e6;
                    *background-color: #d9d9d9;
                }
                .btn:active,
                .btn.active {
                    background-color: #cccccc \9;
                }

                .btn:first-child {
                    *margin-left: 0;
                }
                .btn:hover,
                .btn:focus {
                    color: #333333;
                    text-decoration: none;
                    background-position: 0 -15px;
                    -webkit-transition: background-position 0.1s linear;
                    -moz-transition: background-position 0.1s linear;
                    -o-transition: background-position 0.1s linear;
                    transition: background-position 0.1s linear;
                }
                .btn:focus {
                    outline: thin dotted #333;
                    outline: 5px auto -webkit-focus-ring-color;
                    outline-offset: -2px;
                }
                .btn.active,
                .btn:active {
                    background-image: none;
                    outline: 0;
                    -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
                    -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
                    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
                }
                .btn.disabled,
                .btn[disabled] {
                    cursor: default;
                    background-image: none;
                    opacity: 0.65;
                    filter: alpha(opacity=65);
                    -webkit-box-shadow: none;
                    -moz-box-shadow: none;
                    box-shadow: none;
                }
                [class^="icon-"],
                [class*=" icon-"] {
                    display: inline-block;
                    width: 14px;
                    height: 14px;
                    margin-top: 1px;
                    *margin-right: .3em;
                    line-height: 14px;
                    vertical-align: text-top;
                    background-image: url("/templates/default/images/glyphicons-halflings.png");
                    background-position: 14px 14px;
                    background-repeat: no-repeat;
                }
                .icon-print {
                    background-position: -96px -48px;
                }
                .icon-remove {
                    background-position: -312px 0;
                }
				.main-block {width:194mm; padding:1mm 2mm 2mm 2mm; }
				@media print{
					.navbar{
						display:none;
						visibility: hidden;
					}
				}
			</style>
		</head>
		<body>
        <header>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="navbar-inner">
                    <a class="btn" href="#" onclick="window.print(); return false;">
                        <i class="icon-print"></i>
                         Печать
                    </a>
                    <a class="btn" onclick="window.close();">
                        <i class="icon-remove"></i>
                         Отмена
                    </a>
                </div>
            </nav>
        </header>
		<div class="main">
			<!-- begin 1 block -->
			<div class="main-block">
				<div class="ttle">Заявка №{{query.number}} от {{query.time_open|date('H:i d.m.Y')}}</div>
				<div class="ttle">
						{{query.street_name}}, дом №{{query.house_number}}
						{% if query.initiator == 'number' %}
							{% if component.numbers.numbers != false %}
								, кв. {{number.flat_number}} (л/с №{{number.number}}, {{number.fio}})
							{% endif %}
						{% endif %}
				</div>
				<div class="ttle">{{query.description}}</div>
				<table style="padding:5mm 0mm 0mm 0mm;">
				{% for i in 1..6 %}
					<tr>
								<td>{{i}}</td>
								<td style="border-bottom: 1px solid black; width:70%; height:8mm;"></td>
								<td style="height:8mm;">с</td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>		
								<td style="height:8mm;">до</td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>
								<td style="border-bottom: 1px solid black; width:4%; height:8mm;"></td>		
								<td style="height:8mm;">-</td>
								<td style="border-bottom: 1px solid black; width:15%; height:8mm;"></td>		
								<td style="height:8mm;">г.</td>													
							</tr>
				{% endfor %}
				</table>
				<div style="font-size:6pt; margin: 5mm 0mm 0mm 0mm;">Все необходимые работы выполнены в срок, качественно и в полном объеме. Оплата за выполненые работы с Заявителя не взималась. Заявитель не имеет претензий, связанных с исполнением заявки. С правилами и условиями эффективного и безопасного использования результатов оказанных услуг ( выполненных работ ) заявитель ознакомлен.</div>
				<table style="margin: 2mm 0mm 0mm 0mm;">
					<tr>
						<td>Работы </td>
						<td> принял</td>
						<td style="border-bottom: 1px solid black; width:60%;"></td>
						<td>Подпись</td>
						<td style="border-bottom: 1px solid black; width:20%;"></td>
						<td>Телефон</td>
						<td style="border-bottom: 1px solid black; width:20%;"></td>
					</tr>
				</table>		
		</div>
		<!-- end 1 block, begin 2 block -->
		<div class="main-block">
		{% if query.initiator == 'number' %}
			<div>Владелец {{number.fio}}</div>
		{% endif %}
		{% if query.contact_fio != false or query.contact_telephone != false or query.contact_cellphone != false%}
			<div>Заявку подал:
				{% if query.contact_fio != false %}
					{{query.contact_fio}}
				{% endif %}
				{% if query.contact_telephone != false %}
					тел. {{query.contact_telephone}}
				{% endif %}
				{% if query.contact_cellphone != false %}
					сот. {{query.contact_cellphone}}
				{% endif %}
			</div>
		{% endif %}
			<table style="margin:2mm 0mm 0mm 0mm; width:194mm">
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;">Сотрудники: диспетчер
					{% if component.users != false %}
						{% set creator = component.users.users[component.users.structure[query.id].creator[0]] %}
						{{creator.lastname}} {{creator.firstname}} {{creator.middlename}}
					{% endif %}
					</td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;">Отметки:</td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;"></td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;"></td>
				</tr>
				<tr>
					<td style="border-bottom: 1px solid black; width:49%; height:8mm;"></td>
					<td style="width:1%; height:8mm;"></td>
					<td style="border-bottom: 1px solid black; width:50%; height:8mm;"></td>
				</tr>			
			</table>
		</div>
	<!-- end 2 block -->
	</div></body></html>
{% endif %}