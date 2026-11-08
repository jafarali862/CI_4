<?php
$errors = session('errors') ?? [];
$user   = $user ?? [];
$isEdit = $isEdit ?? false;

// Helper: show error for one column
$err = static function (string $field) use ($errors) {
    return isset($errors[$field])
        ? '<div class="invalid-feedback d-block">' . esc($errors[$field]) . '</div>'
        : '';
};
$cls = static fn (string $f) => isset($errors[$f]) ? 'is-invalid' : '';
?>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control <?= $cls('name') ?>"
               value="<?= esc(old('name', $user['name'] ?? '')) ?>">
        <?= $err('name') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="text" name="email" class="form-control <?= $cls('email') ?>"
               value="<?= esc(old('email', $user['email'] ?? '')) ?>">
        <?= $err('email') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Password <?= $isEdit ? '(leave blank to keep current)' : '' ?></label>
        <input type="password" name="password" class="form-control <?= $cls('password') ?>">
        <?= $err('password') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control <?= $cls('confirm_password') ?>">
        <?= $err('confirm_password') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control <?= $cls('phone') ?>"
               value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
        <?= $err('phone') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Age</label>
        <input type="number" name="age" class="form-control <?= $cls('age') ?>"
               value="<?= esc(old('age', $user['age'] ?? '')) ?>">
        <?= $err('age') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Gender</label>
        <?php $g = old('gender', $user['gender'] ?? ''); ?>
        <select name="gender" class="form-select <?= $cls('gender') ?>">
            <option value="">-- Select --</option>
            <?php foreach (['Male', 'Female', 'Other'] as $opt): ?>
                <option value="<?= $opt ?>" <?= $g === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
        </select>
        <?= $err('gender') ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="dob" class="form-control <?= $cls('dob') ?>"
               value="<?= esc(old('dob', $user['dob'] ?? '')) ?>">
        <?= $err('dob') ?>
    </div>

   <div class="col-md-12">
    <label class="form-label">Profile Picture <?= $isEdit ? '(optional)' : '' ?></label>

    <?php $tempPic = session('temp_pic') ?? ''; ?>
    <?php if ($tempPic): ?>
        <div class="mb-2">
            <img src="<?= base_url('uploads/temp/' . $tempPic) ?>" width="80" class="rounded border">
            <small class="text-success d-block">
                Image already uploaded. Choose another file only if you want to replace it.
            </small>
        </div>
        <input type="hidden" name="temp_pic" value="<?= esc($tempPic) ?>">
    <?php endif; ?>

    <input type="file" name="profile_pic" class="form-control <?= $cls('profile_pic') ?>" accept="image/*">
    <?= $err('profile_pic') ?>

    <?php if ($isEdit && ! empty($user['profile_pic'])): ?>
        <img src="<?= base_url('uploads/profiles/' . $user['profile_pic']) ?>" width="80" class="mt-2 rounded border">
    <?php endif; ?>
</div>
    <div class="col-md-12">
        <label class="form-label">Address</label>
        <textarea name="address" rows="3" class="form-control <?= $cls('address') ?>"><?= esc(old('address', $user['address'] ?? '')) ?></textarea>
        <?= $err('address') ?>
    </div>
</div>