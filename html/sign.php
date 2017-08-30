<div class="wrapper">
    <div class="b-sign">
        <div class="sign">
            <div class="sign__header">
                <h1 class="sign__title">Регистрация</h1>
                <button class="sign__close">×</button>
            </div>
            <div class="sign__body">
                <?php if(isset($error_singup) && count($error_singup) != 0): ?>
                    <div class="error_singup">
                        <?php foreach ($error_singup as $value): ?>
                            <span class="error_singup_span"><?php echo $value; ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <input type="text" name="user_name" value="<?php if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['user_name'])) echo $user_name; ?>" placeholder="Фамилия, Имя" class="sign__input">
                    <input type="text" name="user_login" value="<?php if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['user_login'])) echo $user_login; ?>" placeholder="Логин" class="sign__input sign__input-login">
                    <input type="email" name="user_email" value="<?php if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['user_email'])) echo $user_email; ?>" placeholder="Email" class="sign__input sign__input-email">
                    <input type="password" name="user_password" placeholder="Пароль" class="sign__input sign__input-password">
                    <label class="sign__label"><input type="checkbox" name="user_agree">Запомнить меня на этом компьютере.</label>
                    <input type="submit" name="make_signup" class="sign__input sign__input-submit" value="Зарегистрироваться!">
                </form>
            </div>
        </div>
    </div>
</div>