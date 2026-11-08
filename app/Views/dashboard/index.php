<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Dashboard</h3>
    <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary">Logout</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-3 text-center">
                <img src="<?= base_url('uploads/profiles/' . $user['profile_pic']) ?>"
                     class="img-fluid rounded-circle border mb-3" style="width:160px;height:160px;object-fit:cover;">
                <h5><?= esc($user['name']) ?></h5>
            </div>

            <div class="col-md-9">
                <table class="table table-borderless">
                    <tr><th width="30%">Name</th><td><?= esc($user['name']) ?></td></tr>
                    <tr><th>Email</th><td><?= esc($user['email']) ?></td></tr>
                    <tr><th>Phone</th><td><?= esc($user['phone']) ?></td></tr>
                    <tr><th>Age</th><td><?= esc($user['age']) ?></td></tr>
                    <tr><th>Gender</th><td><?= esc($user['gender']) ?></td></tr>
                    <tr><th>Date of Birth</th><td><?= esc(date('d M Y', strtotime($user['dob']))) ?></td></tr>
                    <tr><th>Address</th><td><?= nl2br(esc($user['address'])) ?></td></tr>
                </table>

                <div class="d-flex gap-2">
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-warning">Edit Profile</a>

                    <form id="deleteForm" action="<?= base_url('profile/delete') ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="button" id="deleteBtn" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('deleteBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Delete your account?',
        text: 'This will permanently remove your profile and photo. You cannot undo this.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then(function (result) {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
});
</script>
<?= $this->endSection() ?>