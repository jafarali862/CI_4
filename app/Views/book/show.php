<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm mx-auto" style="max-width: 850px;">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-4 text-center">
                <?php if ($book['cover_photo']): ?>
                    <img src="<?= base_url('uploads/books/' . $book['cover_photo']) ?>" class="img-fluid rounded border">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h3><?= esc($book['title']) ?></h3>
                <table class="table table-borderless">
                    <tr><th width="35%">Author</th><td><?= esc($book['author']) ?></td></tr>
                    <tr><th>ISBN</th><td><?= esc($book['isbn']) ?></td></tr>
                    <tr><th>Category</th><td><?= esc($book['category']) ?></td></tr>
                    <tr><th>Published Year</th><td><?= esc($book['published_year']) ?></td></tr>
                    <tr><th>Published Date</th><td><?= esc(date('d M Y', strtotime($book['published_date']))) ?></td></tr>
                    <tr><th>Price</th><td><?= esc(number_format((float) $book['price'], 2)) ?></td></tr>
                    <tr><th>Description</th><td><?= nl2br(esc($book['description'] ?? '')) ?></td></tr>
                </table>
                <a href="<?= base_url('book/edit/' . $book['id']) ?>" class="btn btn-warning">Edit</a>
                <a href="<?= base_url('book') ?>" class="btn btn-link">Back</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>