<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h5 class="kt-subheader__title">
                Tarik Data E-Monitoring Sisa Lelang SDA
            </h5>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__head row pt-3">
            <div class="col-md-3 mt-2 mb-2">
                <button class="btn btn-brand btn-elevate btn-sm __handle_tarikData">
                    <i class="la la-download"></i>
                    Tarik Data
                </button>
            </div>
        </div>

        <div class="kt-portlet__body">

            <!--begin::Section-->
            <div class="kt-section">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($data) : ?>
                                <?php foreach ($data as $k => $d) : ?>
                                    <tr>
                                        <td>
                                            <?php echo $d->tahun ?>
                                        </td>
                                        <td class="status">
                                            <?= $d->status == 0 ? "Tidak Aktif" : "Aktif" ?>
                                        </td>
                                        <td>
                                            <button class="btn-sm btn-success aktifkan" data-id="<?= $d->id ?>">Ubah Status</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <!--end::Section-->
        </div>
        <!--end::Form-->
    </div>
</div>


<!-- Modal Tarik Data -->
<div class="modal fade" id="modalTarikData" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTarikDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTarikDataLabel">Tarik Data</h5>
            </div>
            <div class="modal-body">
                <div class="_tarikData_loading">
                    <i class="fa fa-spinner fa-spin"></i>
                    Sedang menarik data
                </div>
                <div class="d-none _tarikData_done">
                    <i class="fa fa-check"></i>
                    Data berhasil di tarik
                </div>
            </div>
            <div class="modal-footer d-none _tarikData_done">
                <a href="<?php echo site_url('preferensi/tarik-data-emon-sisa-lelang-sda'); ?>" class="btn btn-primary">
                    Selesai
                </a>
            </div>
        </div>
    </div>
</div>

<!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
<script>
    var $th = $('.tableFixHead1').find('thead th')
    $('.tableFixHead1').on('scroll', function() {
        $th.css('transform', 'translateY(' + this.scrollTop + 'px)');
    })


    $(document).ready(function() {
        $('.aktifkan').on('click', function() {
            const id = $(this).data('id');
            const status = $(this).data('status');
            const button = $(this);

            $.ajax({
                url: "<?php echo site_url('dokumenpk/setting/berita-acara/update') ?>",
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    id: id,
                    status: status
                }),
                success: function(response) {
                    if (response.success) {
                        // Update status tampilan
                        const statusText = response.new_status == 1 ? "Aktif" : "Tidak Aktif";
                        button.closest('tr').find('.status').text(statusText);
                    } else {
                        alert('Gagal mengubah status.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah status.');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>