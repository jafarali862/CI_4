<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Deleted Books (Trash)</h3>
    <a href="<?= base_url('book') ?>" class="btn btn-outline-secondary">Back to Books</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Title</th><th>Author</th><th>Deleted At</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
            <?php if (! $books): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Trash is empty.</td></tr>
            <?php endif; ?>
            <?php foreach ($books as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= esc($b['title']) ?></td>
                    <td><?= esc($b['author']) ?></td>
                    <td><?= esc($b['deleted_at']) ?></td>
                    <td class="text-end">
                        <form action="<?= base_url('book/restore/' . $b['id']) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-success">Restore</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3"><?= $pager->links() ?></div>

<?= $this->endSection() ?>