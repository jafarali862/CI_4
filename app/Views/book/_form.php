<?php
$errors = session('errors') ?? [];
$book   = $book ?? [];
$isEdit = $isEdit ?? false;

$err = static fn (string $f) => isset($errors[$f])
    ? '<div class="invalid-feedback d-block">' . esc($errors[$f]) . '</div>' : '';
$cls = static fn (string $f) => isset($errors[$f]) ? 'is-invalid' : '';
$val = static fn (string $f) => esc(old($f, $book[$f] ?? ''));
?>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control <?= $cls('title') ?>" value="<?= $val('title') ?>">
        <?= $err('title') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Author</label>
        <input type="text" name="author" class="form-control <?= $cls('author') ?>" value="<?= $val('author') ?>">
        <?= $err('author') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">ISBN (10 or 13 digits)</label>
        <input type="text" name="isbn" class="form-control <?= $cls('isbn') ?>" value="<?= $val('isbn') ?>">
        <?= $err('isbn') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Category</label>
        <?php $c = old('category', $book['category'] ?? ''); ?>
        <select name="category" class="form-select <?= $cls('category') ?>">
            <option value="">-- Select --</option>
            <?php foreach ($categories as $opt): ?>
                <option value="<?= esc($opt) ?>" <?= $c === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
            <?php endforeach; ?>
        </select>
        <?= $err('category') ?>
    </div>

    <div class="col-md-4">
        <label class="form-label">Published Year</label>
        <input type="number" name="published_year" class="form-control <?= $cls('published_year') ?>" value="<?= $val('published_year') ?>">
        <?= $err('published_year') ?>
    </div>

    <div class="col-md-4">
        <label class="form-label">Published Date</label>
        <input type="date" name="published_date" class="form-control <?= $cls('published_date') ?>" value="<?= $val('published_date') ?>">
        <?= $err('published_date') ?>
    </div>

    <div class="col-md-4">
        <label class="form-label">Price</label>
        <input type="text" name="price" class="form-control <?= $cls('price') ?>" value="<?= $val('price') ?>">
        <?= $err('price') ?>
    </div>

    <div class="col-md-12">
        <label class="form-label">Description (optional)</label>
        <textarea name="description" rows="3" class="form-control <?= $cls('description') ?>"><?= $val('description') ?></textarea>
        <?= $err('description') ?>
    </div>

    <div class="col-md-12">
        <label class="form-label">Cover Photo <?= $isEdit ? '(optional, leave empty to keep current)' : '' ?></label>
        <input type="file" name="cover_photo" class="form-control <?= $cls('cover_photo') ?>" accept="image/*">
        <?= $err('cover_photo') ?>
        <?php if ($isEdit && ! empty($book['cover_photo'])): ?>
            <img src="<?= base_url('uploads/books/' . $book['cover_photo']) ?>" width="90" class="mt-2 rounded border">
        <?php endif; ?>
    </div>
</div>