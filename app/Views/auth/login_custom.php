<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Login - Sidik Anomali</title>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body class=" d-flex flex-column bg-white-xs">
    <div class="page page-center">
        <div class="container container-tight py-4">

            <div class="text-center mb-3">
                <a href="<?= base_url() ?>" class="navbar-brand navbar-brand-autodark">
                    <img src="<?= base_url('img/logo.png') ?>" alt="Sidik Anomali" height="80" style="object-fit: contain;">
                </a>
            </div>

            <div class="card card-md shadow-sm">
                <div class="card-status-top bg-primary"></div>

                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Selamat Datang</h2>

                    <?php if (session('error') !== null) : ?>
                        <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
                    <?php elseif (session('errors') !== null) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php if (is_array(session('errors'))) : ?>
                                <?php foreach (session('errors') as $error) : ?>
                                    <?= esc($error) ?>
                                    <br>
                                <?php endforeach ?>
                            <?php else : ?>
                                <?= esc(session('errors')) ?>
                            <?php endif ?>
                        </div>
                    <?php endif ?>

                    <?php if (session('message') !== null) : ?>
                        <div class="alert alert-success" role="alert"><?= esc(session('message')) ?></div>
                    <?php endif ?>

                    <form action="<?= url_to('login') ?>" method="post" autocomplete="off">
                        <?= csrf_field() ?>

                        <!-- email -->
                        <div class="mb-3">
                            <label class="form-label"><?= lang('Auth.email') ?> / <?= lang('Auth.username') ?></label>
                            <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                        </div>

                        <!-- password -->
                        <div class="mb-3">
                            <label class="form-label">
                                <?= lang('Auth.password') ?>
                                <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                                    <span class="form-label-description">
                                        <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.forgotPassword') ?></a>
                                    </span>
                                <?php endif; ?>
                            </label>
                            <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                        </div>

                        <!-- remember me -->
                        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                            <div class="mb-2">
                                <label class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" <?= old('remember') ? 'checked' : '' ?> />
                                    <span class="form-check-label"><?= lang('Auth.rememberMe') ?></span>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <?= lang('Auth.login') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (setting('Auth.allowRegistration')) : ?>
                <div class="text-center text-muted mt-3">
                    <?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a>
                </div>
            <?php endif; ?>

            <div class="text-center text-muted mt-4">
                &copy; <?= date('Y') ?> <strong>BPS Kabupaten Dharmasraya</strong>
            </div>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js">
    </script>
</body>

</html>