<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm mx-auto" style="max-width: 850px;">
    <div class="card-body p-4">
        <h3 class="mb-4">Edit Book</h3>

        <form action="<?= base_url('book/update/' . $book['id']) ?>" method="post" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?= view('book/_form', ['book' => $book, 'categories' => $categories, 'isEdit' => true]) ?>
            <button class="btn btn-primary mt-4">Update Book</button>
            <a href="<?= base_url('book') ?>" class="btn btn-link mt-4">Cancel</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>