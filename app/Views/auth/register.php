<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm mx-auto" style="max-width: 800px;">
    <div class="card-body p-4">
        <h3 class="mb-4">Register</h3>

        <form action="<?= base_url('register') ?>" method="post" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?= $this->include('partials/user_fields') ?>
            <button class="btn btn-primary mt-4">Register</button>
            <a href="<?= base_url('login') ?>" class="btn btn-link mt-4">Already have an account?</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>