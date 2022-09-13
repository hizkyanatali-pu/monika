<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .paginate_button.page-item {
        padding: 0px !important;
    }

    .paginate_button.page-item:hover {
        background-color: #FFF !important;
    }

    ._remove-row-item {
        width: 20px; 
        height: 20px; 
        position: absolute; 
        right: -12.9px; 
        top: 18px; 
        padding: 0px 0px 19px 6px !important
    }

    ._table-form ._remove-row-item {
        padding: 0px 0px 19px 5px !important
    }

    ._remove-row-item .fas {
        font-size: 10px
    }

    .__remove-row-form-rumus-item {
        width: 15px; 
        height: 15px; 
        position: absolute; 
        right: -7px; 
        top: 33px; 
        padding: 0px 0px 0px 4px !important
    }

    .__remove-row-form-rumus-item .fas {
        font-size: 7px;
        margin-top: -7px;
    }
</style>

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <h5 class="kt-subheader__title">
                    Template Dokumen
                </h5>
                <?= csrf_field() ?>
                
                <div>
                    <button 
                        class="btn btn-primary" 
                        data-toggle="modal"
                        data-target="#modalForm"
                    >
                        <i class="fas fa-plus"></i> Buat Template
                    </button>
                </div>
            </div>
            <span class="kt-subheader__separator kt-hidden"></span>

        </div>

    </div>
</div>

<!-- end:: Subheader -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-section mb-0">
                <table 
                    class="table table-bordered" 
                    id="table"
                >
                    <thead>
                        <tr class="text-center">
                            <th width="30px">No</th>
                            <th>Nama Dokumen</th>
                            <th width="100px">Dokumen Untuk</th>
                            <!-- <th width="100px">Keterangan</th> n-->
                            <th width="80px"></th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 12px">
                        <?php
                            foreach ($data as $key => $data) :
                        ?>
                        <tr>
                            <td><?php echo $key+1 ?></td>
                            <td><?php echo $data->title ?></td>
                            <td><?php echo $data->type ?></td>
                            <!-- <td>
                                <select name="status-template" class="form-control" data-id="<?php echo $data->id ?>">
                                    <option value="1" <?php if ($data->status == '1') { ?> selected <?php } ?> >On Going</option>
                                    <option value="0" <?php if ($data->status == '0') { ?> selected <?php } ?> >Selesai</option>
                                </select>
                            </td> -->
                            <td>
                                <button
                                    class="_actionRow btn btn-sm btn-default pt-2 pr-1 pb-2 pl-3"
                                    data-id="<?php echo $data->id ?>"
                                    data-action="prepare-edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    class="_actionRow btn btn-sm btn-danger pt-2 pr-2 pb-2 pl-3"
                                    data-id="<?php echo $data->id ?>"
                                    data-action="prepare-remove"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<table class="d-none" id="_exported_table"></table>
<!-- end:: Content -->



<!-- Modal Form -->
<div class="modal fade" id="modalForm" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content" style="width: 93vw !important">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    <i class="fas fa-file mr-3"></i> Buat Template Dokumen
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row _form-main">
                    <div class="col-md-8 pr-4">
                        <div class="form-group mb-4 d-none">
                            <strong for="judul-dokumen">Dokumen Untuk</strong>
                            <select name="dokumen-type" class="form-control w-25">
                                <option value="-" disabled>Pilih Opsi</option>
                                <option value="satker" selected>Satker</option>
                                <option value="balai">Balai</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <strong for="judul-dokumen">Judul Dokumen</strong>
                            <input type="text" name="judul-dokumen" class="form-control" id="judul-dokumen" placeholder="Masukkan judul dokumen disini">
                        </div>
                        <div>
                            <table class="table table-bordered table striped _table-form">
                                <thead>
                                    <tr>
                                        <th class="text-center bg-purple">SASARAN PROGRAM/SASARAN KEGIATAN/INDIKATOR</th>
                                        <th class="text-center bg-purple" width="140px">TARGET <?php echo $sessionYear ?></th>
                                        <th class="text-center bg-purple" width="140px">Outcome</th>
                                        <th class="text-center bg-purple" width="140px">Rumus</th>
                                    </tr>
                                    <tr style="font-size: 10px">
                                        <th class="text-center p-2">(1)</th>
                                        <th class="text-center p-2">(2)</th>
                                        <th class="text-center p-2">(3)</th>
                                        <th class="text-center p-2">(4)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="d-flex justify-content-end" style="margin-top: -14px">
                                <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-plus"></i>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item _add-form-row" data-type="section_title">Section Title</a>
                                        <a class="dropdown-item _add-form-row" data-type="form">Baris Form</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 pl-4 border-left">
                        <div class="mb-4">
                            <button class="btn btn-sm btn-outline-primary __prepare-pilih-akses-dokumen">
                                Pilih Akses Dokumen
                            </button>
                        </div>
                        <div class="form-group">
                            <strong for="keterangan-dokumen">Keterangan</strong>
                            <textarea 
                                class="form-control"
                                name="keterangan-dokumen" 
                                id="keterangan-dokumen"
                                rows="2" 
                                placeholder="Masukkan keterangan dokumen"
                            ></textarea>
                        </div>

                        <div class="mt-4 pt-4">
                            <h5>Program / Kegiatan</h5>
                            <div class="mt-3">
                                <small>Pilih data yang akan di gunakan</small>
                                <select class="form-control w-50 mt-2" name="kegiatan-used-data">
                                    <option value="" selected disabled>Pilih Data Digunakan</option>
                                    <option value="tgiat">Kegiatan</option>
                                    <option value="tprogram">Program</option>
                                </select>

                                <table class="table table-striped border mt-4 d-none __table-kegiatan">
                                    <thead>
                                        <tr>
                                            <th class="bg-purple text-white">
                                                Nama <label class="_table-kegiatan-title-section">Kegiatan</label>
                                            </th>
                                            <th class="bg-purple text-white" width="60px"></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="_container-select-kegiatan d-none">
                                                    <select class="select2" name="kegiatan" style="width: 400px">
                                                        <?php foreach ($allKegiatan as $key => $dataKegiatan) : ?>
                                                            <option value="<?php echo $dataKegiatan->kdgiat ?>"><?php echo $dataKegiatan->nmgiat ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="_container-select-program d-none">
                                                    <select class="select2" name="program" style="width: 400px">
                                                        <?php foreach ($allProgram as $key => $dataProgram) : ?>
                                                            <option value="<?php echo $dataProgram->kdprogram ?>"  class="d-none" data-jebret="123"><?php echo $dataProgram->nmprogram ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <button name="add-temp-kegiatan" class="btn btn-sm btn-primary pl-3 pr-2 mt-1">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="w-75 d-none">
                            <table class="table table-bordered _table-informasi">
                                <thead>
                                    <tr>
                                        <th class="bg-purple">Informasi</th>
                                    </tr>
                                    <tr>
                                        <td class="bg-secondary">
                                            <input type="text" name="judul-informasi" class="form-control _judul-informasi" placeholder="Judul Informasi" />
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end"  style="margin-top: -14px">
                                <button class="btn btn-sm btn-secondary pr-2 _add-item-informasi">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="_form-pilih-akses-dokumen d-none">
                    <div class="d-flex jextify-content-start">
                        <button class="btn btn-sm btn-outline-danger __back-from-pilih-akses-dokumen">
                            <i class="fas fa-chevron-left"></i> Kembali
                        </button>
                    </div>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="ml-2 mt-3 w-50 _container-satker-select-balai">
                                    <label>Cari By Balai </label>
                                    <select name="search-opsi-akses-satker" class="form-control">
                                    <option value=" " selected>Pilih Satker Dalam Balai</option>
                                    <?php foreach ($allBalai as $keyBalai => $dataBalai) : ?>
                                        <option value="<?= $dataBalai->balaiid ?>"> <?= $dataBalai->balai ?></option>
                                    <?php endforeach ?>
                                    </select>
                                    <!-- <input type="text" name="search-opsi-akses-satker" class="form-control" placeholder="Cari Satker"> -->
                                </div>
                                </div>
                                <div class="col-md-6">
                                        <div class="ml-2 mt-3 w-100">
                                            <label>Cari</label>
                                            <input type="text" name="search-opsi-akses-satker_text" class="form-control" placeholder="Cari Satker">
                                        </div>
                                </div>
                           
                            </div>
                    <div class="mt-4 border" style="height: 45vh; overflow: auto">
                        <table class="table table-bordered">
                            <thead style="position: sticky; top: 0;">
                                <th class="bg-purple" width="30px">
                                    <input type="checkbox" name="check-all-opsi-satker">
                                </th>
                                <th class="bg-purple">Nama Satker</th>
                            </thead>
                            <tbody>
                                <?php foreach ($allSatker as $keySatker => $dataSatker) : ?>
                                    <tr class="_list-opsi-satker _list-satker">
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                name="check-list-opsi-satker" 
                                                class="open-to-check"
                                                value="<?php echo $dataSatker->satkerid ?>"
                                                data-balaiid="<?= $dataSatker->balaiid ?>"
                                            >
                                        </td>
                                        <td>
                                            <label>
                                                <?php echo $dataSatker->satker ?>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php foreach ($allBalai as $keyBalai => $dataBalai) : ?>
                                    <tr class="_list-opsi-satker _list-opsi-balai">
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                name="check-list-opsi-satker" 
                                                class="open-to-check"
                                                value="<?php echo $dataBalai->balaiid ?>"
                                            >
                                        </td>
                                        <td>
                                            <label>
                                                <?php echo $dataBalai->balai ?>
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="save-document" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>

                <button type="button" name="update-document" class="btn btn-success d-none">
                    <i class="fas fa-edit"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Form -->

<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<?php echo script_tag('plugins/datatables/jquery.dataTables.min.js'); ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.js'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var timerUserTyping                        = '',
        element_formTable                      = $('._table-form').find('tbody'),
        element_tableInformasi                 = $('._table-informasi').find('tbody'),
        element_modalForm                      = $('#modalForm')
        element_modalFormFooter                = element_modalForm.find('.modal-footer')
        element_buttonPreparePilihAksesDokumen = $('button.__prepare-pilih-akses-dokumen')
        element_formMain                       = $('._form-main'),
        element_formPilihAksesDokumen          = $('._form-pilih-akses-dokumen'),
        element_checkAllOpsiAksesDokumen       = $('input[type=checkbox][name=check-all-opsi-satker]'),
        element_checkboxOpsiAksesDokumenShowed = $('input.open-to-check[type=checkbox][name=check-list-opsi-satker]')
        element_selectDokumenType              = $("select[name=dokumen-type]"),
        element_tableKegiatan                  = $(".__table-kegiatan"),
        csrfName                               = '<?= csrf_token() ?>',
        csrfHash                               = '<?= csrf_hash() ?>'


    $(document).ready(function () {
        $('#table').DataTable({
            scrollX: true
        })

        $('select.select2').select2();

        element_modalForm.on('hide.bs.modal', function() {
            setTimeout(() => {
                $('input[name=csrf_test_name]').val('')
                $('input[name=judul-dokumen]').val('')
                $('textarea[name=keterangan-dokumen]').val('')
                $('input[name=judul-informasi]').val('')

                // reset select dokumen type
                element_selectDokumenType.find("option[value='satker']").removeAttr('disabled')
                element_selectDokumenType.val('satker').change()
                element_selectDokumenType.find("option[value='satker']").prop('disabled', 'disabled')

                element_formTable.empty()
                element_tableInformasi.empty()
                element_checkboxOpsiAksesDokumenShowed.prop('checked', false)

                $('button[name=save-document]').removeClass('d-none')
                $('button[name=update-document]').addClass('d-none')
                
                element_formMain.removeClass('d-none')
                element_formPilihAksesDokumen.addClass('d-none')
                element_modalFormFooter.removeClass('d-none')

                $('._container-satker-select-balai').removeClass('d-none');
                $('._list-opsi-satker._list-satker').removeClass('d-none');
                $('._list-opsi-balai').addClass('d-none');

                location.reload()
            }, 400)
        })
    })



    $(document).on('change', 'select[name=dokumen-type]', function() {
        setOpsiHakAkses()
    })



    $('._add-form-row').click(function() {
        let row = ''

        switch ($(this).data('type')) {
            case 'section_title':
                row = render_rowTitleSection()
                break;
            
            case 'form':
                row = render_rowForm()
                break;
        }
        
        element_formTable.append(row)
    })



    $('._add-item-informasi').click(function() {
        element_tableInformasi.append(render_itemInformasi())
    })



    $(document).on('click', '._remove-row-item', function() {
        let parent = $(this).parents('tr')
        parent.remove()
    })



    $('button._actionRow').on('click', function() {
        let dataID = $(this).data('id')

        switch ($(this).data('action')) {
            case 'prepare-edit':
                prepare_updateForm(dataID)
                break;
            
            case 'prepare-remove':
                deleteDocument(dataID)
                break;
        }
    })



    element_buttonPreparePilihAksesDokumen.on('click', function() {
        element_formMain.addClass('d-none')
        element_formPilihAksesDokumen.removeClass('d-none')
        element_modalFormFooter.addClass('d-none')
    })



    $('button.__back-from-pilih-akses-dokumen').on('click', function() {
        element_formPilihAksesDokumen.addClass('d-none')
        element_formMain.removeClass('d-none')
        element_modalFormFooter.removeClass('d-none')

        if (element_checkboxOpsiAksesDokumenShowed.filter(':checked').length > 0) {
            element_buttonPreparePilihAksesDokumen.html('<i class="fa fa-check"></i> Pilih Akses Dokumen')
        }
        else {
            element_buttonPreparePilihAksesDokumen.find('i').remove()
        }
    })



    $('select[name=search-opsi-akses-satker]').on('change', function() {
        clearTimeout(timerUserTyping)
        timerUserTyping = setTimeout(() => {
            let searchInput = $(this).val();

            $('._list-opsi-satker').each((key, element) => {
                let listElement  = $(element),
                listText     = listElement.find('label').text(),
                listCheckbox = listElement.find('input[type=checkbox][name=check-list-opsi-satker]')
                
                if (listCheckbox.data("balaiid") == searchInput || searchInput == " ") {
                    listElement.removeClass('d-none')
                    listCheckbox.addClass('open-to-check')
                }
                else {
                    listElement.addClass('d-none')
                    listCheckbox.removeClass('open-to-check')
                }
            })

            let checkboxShowedIsChecked = element_checkboxOpsiAksesDokumenShowed.not(':checked').length,
                setPropCheckedAll       = checkboxShowedIsChecked > 0 ? false : true
            
            element_checkAllOpsiAksesDokumen.prop('checked', setPropCheckedAll)

            setOpsiHakAkses('balai')
        }, 700)
    })



    $('input[name=search-opsi-akses-satker_text]').on('keyup', function() {
        clearTimeout(timerUserTyping)
        timerUserTyping = setTimeout(() => {
            let searchInput = $(this).val();

            $('._list-opsi-satker').each((key, element) => {
                let listElement  = $(element),
                    listText     = listElement.find('label').text(),
                    listCheckbox = listElement.find('input[type=checkbox][name=check-list-opsi-satker]')
                
                if (listText.toLowerCase().includes(searchInput.toLowerCase())) {
                    listElement.removeClass('d-none')
                    listCheckbox.addClass('open-to-check')
                }
                else {
                    listElement.addClass('d-none')
                    listCheckbox.removeClass('open-to-check')
                }
            })

            let checkboxShowedIsChecked = element_checkboxOpsiAksesDokumenShowed.not(':checked').length,
                setPropCheckedAll       = checkboxShowedIsChecked > 0 ? false : true
            
            element_checkAllOpsiAksesDokumen.prop('checked', setPropCheckedAll)

            let hakAksesOpsiParam = $('select[name=dokumen-type]').val() == 'balai' ? 'satker' : 'balai'
            console.log(hakAksesOpsiParam);
            setOpsiHakAkses(hakAksesOpsiParam)
        }, 700)
    })



    element_checkAllOpsiAksesDokumen.on('change', function() {
        $('input.open-to-check[type=checkbox][name=check-list-opsi-satker]').prop('checked', this.checked)
    })



    $(document).on('change', 'select[name=kegiatan-used-data]', function() {
        element_tableKegiatan.removeClass('d-none')

        switch ($(this).val()) {
            case 'tgiat':
                $('._container-select-kegiatan').removeClass('d-none')
                $('._container-select-program').addClass('d-none')
                break;
            
            case 'tprogram':
                $('._container-select-program').removeClass('d-none')
                $('._container-select-kegiatan').addClass('d-none')
                break;
        }

        $('._table-kegiatan-title-section').text($(this).find('option:selected').text())
        element_tableKegiatan.find('tbody').empty()
    })



    $(document).on('click', 'button[name=add-temp-kegiatan]', function() {
        let selectName             = $('select[name=kegiatan-used-data]').val() == 'tgiat' ? 'kegiatan' : 'program',
            element_selectKegiatan = $('select[name='+selectName+']')

        render_rowTableKegiatan(params = {
            idKegiatan  : element_selectKegiatan.val(),
            namaKegiatan: element_selectKegiatan.find('option:selected').text(),
        })
    })



    $(document).on('click', 'button[name=remove-temp-kegiatan]', function() {
        $(this).parents('tr').remove()
    })



    $('button[name=save-document]').click(function() {
        if (formValidation()) {
            let form = get_formInputValue()
            
            $.ajax({
                url: "<?php echo site_url('dokumenpk/template/create') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: form,
                success: (res) => {
                    if (res.status) {
                        location.reload()
                    }
                    else {
                        alert('Terjadi kesalahan pada sistem')
                    }
                },
                fail: (xhr) => {
                    alert('Terjadi kesalahan pada sistem')
                    console.log(xhr)
                }
            })
        }
    })



    $('button[name=update-document]').click(function() {
        console.log($('input[name=csrf_test_name]').val())
        if (formValidation()) {
            let form = get_formInputValue()
            form.append('dataId', $(this).data('id'))

            $.ajax({
                url: '<?php echo site_url('dokumenpk/template/update') ?>',
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: form,
                success: (res) => {
                    if (res.status) {
                        location.reload()
                    }
                    else {
                        alert('Terjadi kesalahan pada sistem')
                    }
                },
                fail: (xhr) => {
                    alert('Terjadi kesalahan pada sistem')
                    console.log(xhr)
                }
            })
        }
    })
    
    
    
    // $(document).on('change', 'select[name=status-template]', function() {
    //     let form = new FormData()

    //     form.append('csrf_test_name', $('input[name=csrf_test_name]').val())
    //     form.append('dataId', $(this).data('id'))
    //     form.append('newStatus', $(this).val())

    //     $.ajax({
    //         url: '<?php echo site_url('dokumenpk/template/update-status') ?>',
    //         type: 'POST',
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         data: form,
    //         success: (res) => {
    //             if (res.status) {
    //                 location.reload()
    //             }
    //             else {
    //                 alert('Terjadi kesalahan pada sistem')
    //             }
    //         },
    //         fail: (xhr) => {
    //             alert('Terjadi kesalahan pada sistem')
    //             console.log(xhr)
    //         }
    //     })
    // })



    function deleteDocument(_dataID) {
        Swal.fire({
            title: 'Menghapus Dokumen',
            text: "Apakah anda yakin menghapus dokumen ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, hapus data ini!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/template/delete') ?>",
                    type: 'POST',
                    data: { 
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        id            : _dataID
                    },
                    success: (res) => {
                        if (res.status) {
                            Swal.fire({
                                title: 'Berhasil Menghapus Dokumen',
                                text: 'Data dokumen telah di hapus.',
                                type: 'success',
                                showConfirmButton: false
                            })

                            setTimeout(() => {
                                location.reload()
                            }, 800)
                        }
                    },
                    fail: () => {
                        alert('Terjadi kesalahan pada sistem')
                    }
                })
            }
        })
    }



    function prepare_updateForm(_dataID) {
        let element_updateButton = $('button[name=update-document]')

        $('button[name=save-document]').addClass('d-none')
        element_updateButton.removeClass('d-none')

        element_updateButton.attr('data-id', _dataID)

        element_modalForm.modal('show')

        $.ajax({
            url: "<?php echo site_url('dokumenpk/template/detail/') ?>" + _dataID,
            type: 'GET',
            success: (res) => {
                set_formInputValue(res)
            },
            fail: (xhr) => {
                alert('Terjadi kesalahan pada sistem')
                console.log(xhr)
            }
        })
    }





    function get_formInputValue() {
        let form = new FormData()

        form.append('csrf_test_name', $('input[name=csrf_test_name]').val())
        form.append('type', element_selectDokumenType.val())
        form.append('title', $('input[name=judul-dokumen]').val())
        form.append('keterangan', $('textarea[name=keterangan-dokumen]').val())
        form.append('kegiatan_table_ref', $('select[name=kegiatan-used-data]').val())
        form.append('info_title', 'KEGIATAN')
        // form.append('info_title', $('input[name=judul-informasi]').val())

        tableForm_to_array().forEach((data, key) => {
            form.append('formTable_title[]', data.title)
            form.append('formTable_targetSatuan[]', data.target_satuan)
            form.append('formTable_outcomeSatuan[]', data.outcome_satuan)
            form.append('formTable_rumus[]', data.rumus)
            form.append('formTable_type[]', data.type)
        })

        tableKegiatan_to_array().forEach((data, key) => {
            form.append('kegiatan_id[]', data.id)
            form.append('kegiatan_nama[]', data.nama)
            form.append('kegiatan_rev[]', data.rev)
        })

        itemInformasi_to_array().forEach((data, key) => {
            form.append('info_item[]', data)
        })

        element_checkboxOpsiAksesDokumenShowed.filter(':checked').each((key, element) => {
            form.append('akses[]', $(element).val())
        })
        
        return form
    }
    
    
    
    function formValidation() {
        if (element_checkboxOpsiAksesDokumenShowed.filter(':checked').length <= 0) {
            Swal.fire(
                'Peringatan',
                'Hak akses template belum di sesuaikan',
                'warning'
            )
            return false
        }

        if (tableKegiatan_to_array().length <= 0) {
            Swal.fire(
                'Peringatan',
                'Kegiatan belum di pilih',
                'warning'
            )
            return false
        }

        return true
    }



    function set_formInputValue(_data) {
        element_formTable.empty()
        element_tableInformasi.empty()
        element_checkboxOpsiAksesDokumenShowed.prop('checked', false)
        
        $('input[name=judul-dokumen]').val(_data.template.title)
        $('textarea[name=keterangan-dokumen]').val(_data.template.keterangan)
        $('select[name=kegiatan-used-data]').val(_data.template.kegiatan_table_ref).trigger("change")
        $('input[name=judul-informasi]').val(_data.template.info_title)
        element_selectDokumenType.val(_data.template.type).change()

        let indexDataRumus = 0
        _data.rows.forEach((data, key) => {
            if (data.type == "section_title") {
                element_formTable.append(render_rowTitleSection({
                    titleSection: data.title
                }))
            }
            else {
                element_formTable.append(render_rowForm({
                    namaItem     : data.title,
                    targetSatuan : data.target_satuan,
                    outcomeSatuan: data.outcome_satuan
                }))
                
                if (parseInt(data.rumusJml) > 0) {
                    $('input._rumus:last').val(_data.rowRumus[indexDataRumus].rumus)
                    indexDataRumus++
                }
            }
        })

        _data.kegiatan.forEach((data, key) => {
            render_rowTableKegiatan({
                idKegiatan  : data.id,
                namaKegiatan: data.nama
            })
        })

        _data.info.forEach((data, key) => {
            element_tableInformasi.append(render_itemInformasi({
                itemValue: data.info
            }))
        });


        _data.akses.forEach((data, key) => {
            $('input.open-to-check[type=checkbox][name=check-list-opsi-satker][value='+data.rev_id+']').prop('checked', true)
        });
    }


    
    function tableForm_to_array() {
        let tempArrayForm = []

        element_formTable.find('tr').each((key, element) => {
            let title          = '',
                target_satuan  = '',
                outcome_satuan = '',
                rumus          = [],
                type           = ''

            if ($(element).hasClass('_title-section')) {
                title = $(element).find('._title-section').val()
                type  = 'section_title'
            }
            else {
                title          = $(element).find('._nama-item').val()
                target_satuan  = $(element).find('._target-satuan').val()
                outcome_satuan = $(element).find('._outcome-satuan').val()
                type           = 'form'
                
                $(element).find('._rumus').each((key, rumusElement) => {
                    rumus.push($(rumusElement).val())
                })
            }

            tempArrayForm.push({
                title         : title,
                target_satuan : target_satuan,
                outcome_satuan: outcome_satuan,
                rumus         : rumus,
                type          : type
            })
        })
        
        return tempArrayForm
    }



    function tableKegiatan_to_array() {
        let tempArray = []

        element_tableKegiatan.find('tbody').find('tr').each((key, element) => {
            tempArray.push({
                id  : $(element).data('kegiatan-id'),
                nama: $(element).data('kegiatan-nama'),
                rev : $('select[name=kegiatan-used-data]').val()
            })
        })

        return tempArray
    }

    

    function itemInformasi_to_array() {
        let tempArrayForm = []

        element_tableInformasi.find('tr').each((key, element) => {
            tempArrayForm.push($(element).find('._item-informasi').val())
        })

        return tempArrayForm
    }



    function render_rowTitleSection(params = {
        titleSection: ''
    }) {
        let titleSection = params.hasOwnProperty('titleSection') ? params.titleSection : ''

        return `
            <tr class="_title-section">
                <td colspan="4" class="bg-secondary" style="position: relative">
                    <input type="text" class="form-control _title-section" placeholder="Masukkan title section" value="${titleSection}">
                    
                    <button class="btn btn-danger rounded-circle _remove-row-item">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `
    }



    function render_rowForm(params = {
        namaItem     : '',
        targetSatuan : '',
        outcomeSatuan: ''
    }) {
        let namaItem      = params.hasOwnProperty('namaItem') ? params.namaItem : '',
            targetSatuan  = params.hasOwnProperty('targetSatuan') ? params.targetSatuan : '',
            outcomeSatuan = params.hasOwnProperty('outcomeSatuan') ? params.outcomeSatuan : ''

        return `
            <tr class="_row-form">
                <td>
                    <input type="text" class="form-control _nama-item" placeholder="Nama item" value="${namaItem}">
                </td>
                <td>
                    <input type="text" class="form-control _target-satuan" placeholder="Satuan" value="${targetSatuan}">
                </td>
                <td style="position: relative">
                    <input type="text" class="form-control _outcome-satuan" placeholder="Satuan" value="${outcomeSatuan}">
                </td>
                <td class="rumus" style="position: relative">
                    <div class="_container-row-form-rumus">
                        <div>
                            <input type="text" class="form-control _rumus" placeholder="Tulis Rumus" value="">
                        </div>
                    </div>

                    <button class="btn btn-danger rounded-circle _remove-row-item">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `
    }



    function render_itemInformasi(params = {
        itemValue: ''
    }) {
        let itemValue = params.hasOwnProperty('itemValue') ? params.itemValue : ''

        return `
            <tr>
                <td style="position: relative">
                    <input type="text" class="form-control _item-informasi" placeholder="Item Informasi" value="${itemValue}" />
                    <button class="btn btn-danger rounded-circle _remove-row-item">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `
    }
    
    
    
    function setOpsiHakAkses(type = 'all') {
        switch ($('select[name=dokumen-type]').val()) {
            case 'satker':
                if (type == 'all') $('._container-satker-select-balai').removeClass('d-none');
                if (type == 'all' || type == 'satker') $('._list-opsi-satker._list-satker').removeClass('d-none');
                if (type == 'all' || type == 'balai') $('._list-opsi-balai').addClass('d-none');
                break;

            case 'balai':
                if (type == 'all') $('._container-satker-select-balai').addClass('d-none');
                if (type == 'all' || type == 'satker') $('._list-opsi-satker._list-satker').addClass('d-none');
                if (type == 'all' || type == 'balai') $('._list-opsi-balai').removeClass('d-none');
                break;
        }
    }



    



    function render_rowTableKegiatan(params = {
        idKegiatan  : '',
        namaKegiatan: ''
    })
    {
        $('.__table-kegiatan').find('tbody').append(`
            <tr 
                data-kegiatan-id="${params.idKegiatan}"
                data-kegiatan-nama="${params.namaKegiatan}"
            >
                <td class="align-middle">
                    ${params.namaKegiatan}
                </td>
                <td class="text-right align-middle">
                    <button class="btn btn-sm btn-outline-danger pl-3 pr-2" name="remove-temp-kegiatan">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `)
    }
</script>
<?= $this->endSection() ?>