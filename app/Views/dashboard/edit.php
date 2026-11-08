<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm mx-auto" style="max-width: 800px;">
    <div class="card-body p-4">
        <h3 class="mb-4">Edit Profile</h3>

        <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?= $this->include('partials/user_fields', ['user' => $user, 'isEdit' => true]) ?>
            <button class="btn btn-primary mt-4">Update</button>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-link mt-4">Cancel</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>