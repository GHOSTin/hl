<div class="row-fluid">
    <div class="span6">
        <form action="/?p=auth.login" method="post" class="form-horizontal">
            <legend>Вход в систему</legend>
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="login">Пользователь</label>
                    <div class="controls">
                        <input class="input span9" type="text" id="login" name="login" placeholder="Логин" autofocus/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password">Пароль</label>
                    <div class="controls">
                        <input class="input span9" type="password" id="password" name="password" placeholder="Пароль"/>
                    </div>
                </div>
                <div>
                    <input type="submit" class="btn btn-primary offset8" value="Войти" />
                </div>
            </fieldset>
        </form>
    </div>
</div>