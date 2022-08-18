<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<?php echo script_tag('plugins/datatables/dataTables.bootstrap4.min.css'); ?>
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
        <div class="modal-content">
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
                    <div class="col-md-7 pr-4">
                        <div class="form-group mb-4">
                            <strong for="judul-dokumen">Dokumen Untuk</strong>
                            <select name="dokumen-type" class="form-control w-25">
                                <option value="-" selected disabled>Pilih Opsi</option>
                                <option value="satker">Satker</option>
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
                                    </tr>
                                    <tr style="font-size: 10px">
                                        <th class="text-center p-2">(1)</th>
                                        <th class="text-center p-2">(2)</th>
                                        <th class="text-center p-2">(3)</th>
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

                    <div class="col-md-5 pl-4 border-left">
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
                        <div class="w-75">
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
                                <div class="ml-2 mt-3 w-50">
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
                                    <tr class="_list-opsi-satker">
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
                                            <label><?php echo $dataSatker->satker ?></label>
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
        element_selectDokumenType = $("select[name=dokumen-type]"),
        csrfName = '<?= csrf_token() ?>',
        csrfHash = '<?= csrf_hash() ?>'


    $(document).ready(function () {
        $('#table').DataTable({
            scrollX: true
        })

        element_modalForm.on('hide.bs.modal', function() {
            setTimeout(() => {
                $('input[name=csrf_test_name]').val('')
                $('input[name=judul-dokumen]').val('')
                $('textarea[name=keterangan-dokumen]').val('')
                $('input[name=judul-informasi]').val('')

                // reset select dokumen type
                element_selectDokumenType.find("option[value='-']").removeAttr('disabled')
                element_selectDokumenType.val('-').change()
                element_selectDokumenType.find("option[value='-']").prop('disabled', 'disabled')

                element_formTable.empty()
                element_tableInformasi.empty()
                element_checkboxOpsiAksesDokumenShowed.prop('checked', false)

                $('button[name=save-document]').removeClass('d-none')
                $('button[name=update-document]').addClass('d-none')
                
                element_formMain.removeClass('d-none')
                element_formPilihAksesDokumen.addClass('d-none')
                element_modalFormFooter.removeClass('d-none')
            }, 400)
        })
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
        }, 700)
    })



    element_checkAllOpsiAksesDokumen.on('change', function() {
        $('input.open-to-check[type=checkbox][name=check-list-opsi-satker]').prop('checked', this.checked)
    })



    $('button[name=save-document]').click(function() {
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
    })



    $('button[name=update-document]').click(function() {
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
    })



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
        form.append('info_title', $('input[name=judul-informasi]').val())

        tableForm_to_array().forEach((data, key) => {
            form.append('formTable_title[]', data.title)
            form.append('formTable_targetSatuan[]', data.target_satuan)
            form.append('formTable_outcomeSatuan[]', data.outcome_satuan)
            form.append('formTable_type[]', data.type)
        })

        itemInformasi_to_array().forEach((data, key) => {
            form.append('info_item[]', data)
        })

        element_checkboxOpsiAksesDokumenShowed.filter(':checked').each((key, element) => {
            form.append('akses[]', $(element).val())
        })
        
        return form
    }



    function set_formInputValue(_data) {
        element_formTable.empty()
        element_tableInformasi.empty()
        element_checkboxOpsiAksesDokumenShowed.prop('checked', false)

        $('input[name=judul-dokumen]').val(_data.template.title)
        $('textarea[name=keterangan-dokumen]').val(_data.template.keterangan)
        $('input[name=judul-informasi]').val(_data.template.info_title)
        element_selectDokumenType.val(_data.template.type).change()

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
            }
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
            let title = '',
                target_satuan = '',
                outcome_satuan = '',
                type = ''

            if ($(element).hasClass('_title-section')) {
                title = $(element).find('._title-section').val()
                type  = 'section_title'
            }
            else {
                title          = $(element).find('._nama-item').val()
                target_satuan  = $(element).find('._target-satuan').val()
                outcome_satuan = $(element).find('._outcome-satuan').val()
                type           = 'form'
            }

            tempArrayForm.push({
                title         : title,
                target_satuan : target_satuan,
                outcome_satuan: outcome_satuan,
                type          : type
            })
        })
        
        return tempArrayForm
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
                <td colspan="3" class="bg-secondary" style="position: relative">
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
</script>
<?= $this->endSection() ?>