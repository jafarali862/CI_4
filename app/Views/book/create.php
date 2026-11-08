<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm mx-auto" style="max-width: 850px;">
    <div class="card-body p-4">
        <h3 class="mb-4">Add Book</h3>

        <form action="<?= base_url('book/store') ?>" method="post" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?= view('book/_form', ['categories' => $categories]) ?>
            <button class="btn btn-primary mt-4">Save Book</button>
            <a href="<?= base_url('book') ?>" class="btn btn-link mt-4">Cancel</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>