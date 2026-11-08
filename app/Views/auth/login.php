<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$errors = session('errors') ?? [];
?>

<div class="card shadow-sm mx-auto" style="max-width: 450px;">
    <div class="card-body p-4">
        <h3 class="mb-4">Login</h3>

        <form action="<?= base_url('login') ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" value="<?= esc(old('email')) ?>"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                <?php endif; ?>
            </div>

            <button class="btn btn-primary w-100">Login</button>
            <a href="<?= base_url('register') ?>" class="btn btn-link w-100 mt-2">Create an account</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>