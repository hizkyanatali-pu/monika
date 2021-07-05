<?php if (session()->has('success')) : ?>
    <div class="alert alert-success">
        <?= session('success') ?>
    </div>
<?php endif ?>
<?php if (session()->has('error')) : ?>
    <div class="alert alert-danger error">
        <?= session('error') ?>
    </div>
<?php endif ?>
<?php if (session()->has('errors')) : ?>
    <ul class="notification">
    <?php foreach (session('errors') as $error) : ?>
        <li class="alert alert-danger error"><?= $error ?></li>
    <?php endforeach ?>
    </ul>
<?php endif ?>