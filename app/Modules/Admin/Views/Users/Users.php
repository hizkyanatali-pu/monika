<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>
    <?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>

    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Users Management </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                
            </div>
           
        </div>
    </div>

    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        User List
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            
                            &nbsp;
                            <a href="<?= route_to('users/create') ?>" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                               Add New
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin::Section-->
                <div class="kt-section">
                    <div class="kt-section__info">
                    <?= view('admin/partials/notifications') ?>
                    </div>
                    <div class="kt-section__content">
                        <div>
                            <table class="table table-bordered" id="table">
                                <thead>
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th>ID Pengguna</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>nip</th>
                                        <th>Telpon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($users): ?>
                                    <?php 
                                    foreach($users as $key => $data): ?>
                                    <tr>
                                        <!-- <th scope="row"><?php echo $key+1; ?></th> -->
                                        <td><?php echo $data['idpengguna']; ?></td>
                                        <td><?php echo $data['nama']; ?></td>
                                        <td><?php echo $data['email']; ?></td>
                                        <td><?php echo $data['nip']; ?></td>
                                        <td><?php echo $data['telpon']; ?></td>
                                        <td>
                                            <span class="btn btn-bold btn-sm btn-font-sm  btn-label-<?= ($data['aktif'] ==="1") ? 'success':'danger';?>" >
                                                <?= ($data['aktif'] ==="1") ? 'Aktif':'Tidak Aktif';?>
                                            </span>
                                        </td>
                                        <td>
                                            <a class="kt-nav__link  btn btn-bold btn-sm btn-font-sm  btn-label-primary" href="<?= site_url('users/edit/'.$data['uid']); ?>"><i
                                                    class="kt-nav__link-icon flaticon2-contract"></i>
                                                <span class="kt-nav__link-text">Edit</span></a>

                                            <a class="kt-nav__link btn btn-bold btn-sm btn-font-sm  btn-label-danger " href="<?= site_url('users/delete/'.$data['uid']); ?>"><i
                                                    class="kt-nav__link-icon flaticon2-trash"></i><span
                                                    class="kt-nav__link-text">Delete</span></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php //echo $pager->links() ?>
                    </div>
                </div>

                <!--end::Section-->
            </div>

            <!--end::Form-->
        </div>
    </div>

    <!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
    <?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
    <?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                scrollX: true
            })
        })
    </script>
<?= $this->endSection() ?>