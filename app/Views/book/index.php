<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$total   = $pager->getTotal();
$perPage = $pager->getPerPage();
$start   = ($pager->getCurrentPage() - 1) * $perPage;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h3 class="mb-0">Books</h3>
    <div class="d-flex gap-2">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Dashboard</a>
        <a href="<?= base_url('book/trash') ?>" class="btn btn-outline-danger">Trash</a>
        <a href="<?= base_url('book/create') ?>" class="btn btn-primary">+ Add Book</a>
    </div>
</div>

<form method="get" action="<?= base_url('book') ?>" class="input-group mb-3">
    <input type="text" name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Search title, author or ISBN">
    <button class="btn btn-secondary">Search</button>
</form>

<div class="small text-muted mb-2">
    Showing <?= $total ? $start + 1 : 0 ?> to <?= min($start + $perPage, $total) ?> of <?= $total ?> books
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Cover</th><th>Title</th><th>Author</th>
                    <th>Year</th><th>Date</th><th>Price</th><th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (! $books): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No books found.</td></tr>
            <?php endif; ?>

            <?php foreach ($books as $i => $b): ?>
                <tr>
                    <td><?= $start + $i + 1 ?></td>
                    <td>
                        <?php if ($b['cover_photo']): ?>
                            <img src="<?= base_url('uploads/books/' . $b['cover_photo']) ?>" width="45" height="60" style="object-fit:cover" class="rounded border">
                        <?php endif; ?>
                    </td>
                    <td><?= esc($b['title']) ?></td>
                    <td><?= esc($b['author']) ?></td>
                    <td><?= esc($b['published_year']) ?></td>
                    <td><?= esc(date('d M Y', strtotime($b['published_date']))) ?></td>
                    <td><?= esc(number_format((float) $b['price'], 2)) ?></td>
                    <td class="text-end text-nowrap">
                        <a href="<?= base_url('book/show/' . $b['id']) ?>" class="btn btn-sm btn-info">View</a>
                        <a href="<?= base_url('book/edit/' . $b['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form action="<?= base_url('book/delete/' . $b['id']) ?>" method="post" class="d-inline delete-form">
                            <?= csrf_field() ?>
                            <button type="button" class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pager->getPageCount() > 1): ?>
    <div class="mt-3"><?= $pager->only(['q'])->links('default', 'bootstrap_full') ?></div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var form = btn.closest('form');
        Swal.fire({
            title: 'Delete this book?',
            text: 'It will be moved to the trash and can be restored later.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            reverseButtons: true
        }).then(function (r) { if (r.isConfirmed) form.submit(); });
    });
});
</script>
<?= $this->endSection() ?>