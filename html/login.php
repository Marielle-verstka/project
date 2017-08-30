<div class="wrapper">
    <div class="b-sign">
        <div class="sign">
            <div class="sign__header">
                <h1 class="sign__title">Авторизация</h1>
                <button class="sign__close">×</button>
            </div>
            <div class="sign__body">
                <form action="" method="post">
                    <?php
                        $user_login_auto = '';
                        if (isset($_COOKIE['about'])) {
                            $idss = unserialize($_COOKIE['about']);
                            $user_login_auto = $idss['login'];
                        }
                    ?>
                    <input type="text" name="user_login" value="<?php echo $user_login_auto; ?>" placeholder="Логин" class="sign__input sign__input-login">
                    <input type="password" name="user_password" placeholder="Пароль" class="sign__input sign__input-password">
                    <input type="submit" name="make_login" class="sign__input sign__input-submit" value="Войти!">
                </form>
            </div>
        </div>
    </div>
</div>