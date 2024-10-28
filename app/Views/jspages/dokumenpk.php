<script src="https://unpkg.com/imask"></script>

<script>
    $(window).on('beforeunload', function() {
        sessionStorage.clear();
    });

    var paramsBtnPaket = "";

    var date = new Date(),
        element_modalForm = $('#modalForm'),
        element_modalDialog = element_modalForm.find('.modal-dialog'),
        element_modalFooter = element_modalForm.find('.modal-footer'),
        element_modalFormChooseTemplate = element_modalForm.find('#choose-template'),
        element_modalFormMakeDokumen = element_modalForm.find('#make-dokumen'),
        element_modalFormTitle = element_modalForm.find('.modal-title'),
        element_modalFormBackChooseTemplate = element_modalForm.find('.__back-pilih-dokumen'),
        element_modalPreviewCetakDokumen = $('#modal-preview-cetak'),
        element_btnSaveDokumen = $('.__save-dokumen'),
        element_btnSaveEditDokumen = $('.__save-update-dokumen'),
        element_btnChoosePaket = $('.paket')

    $(document).ready(function() {
        $('#table').DataTable({
            ordering: false,
            scrollX: true
        })
        $('#modalForm').on('hidden.bs.modal', function() {
            $('.__save-dokumen').removeClass('d-none')
            $('.__save-update-dokumen').addClass('d-none')
            $('.container-list-revision-message').addClass('d-none')
            prepareForm_reset()
        })

        $('.modal').on('shown.bs.modal', function() {
            $(document).off('focusin.modal');
        });

    })

    // $(document).on('change', '.select-target-satuan', function() {
    //     // Mengambil nilai terpilih dari elemen select
    //     var nilaiTerpilih = $(this).val();
    //     // Memasukkan nilai ke dalam atribut data-outputsatuan pada tombol
    //     $('.paket').attr('data-outputsatuan', nilaiTerpilih);
    // });


    $(document).on('change', 'select[name=filter-satker]', function() {
        window.location.href = "<?php echo base_url('dokumenpk-balai-satker') ?>/" + $(this).val()
    })



    $(document).on('click', '.__opsi-template', function() {

        if ($(this).data('type') == "uptBalai-add") {
            paramsBtnPaket = "uptBalai-add";

        }

        if ($(this).data('available') == true) {
            if ($(this).data('balai-create-satker') == undefined) {
                $('#modalForm').modal('show')
                let elements_optionListDokumen = $('.__buat-dokumen-pilih-template')
                if (elements_optionListDokumen.length == 1) {
                    elements_optionListDokumen.eq(0).trigger('click')
                }

            } else {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/get-list-template-buat-dokumen') ?>" + "/satker/" + $(this).data('balai-create-satker'),
                    type: 'GET',
                    success: (res) => {
                        let renderListTemplate = ''

                        res.templateDokumen.forEach((data, index) => {
                            renderListTemplate += `
                                <a 
                                    class="list-group-item list-group-item-action __buat-dokumen-pilih-template" 
                                    href="javascript:void(0)" 
                                    data-id="${data.id}"
                                >
                                    ${data.title}
                                </a>
                            `
                        });
                        $('#choose-template').html(renderListTemplate)

                        $('.__opsi-template').attr('data-available', res.templateAvailable)

                        $('#modalForm').modal('show')

                        let elements_optionListDokumen = $('.__buat-dokumen-pilih-template')
                        if (elements_optionListDokumen.length == 1) {
                            elements_optionListDokumen.eq(0).trigger('click')
                        }
                    }
                })
            }
        } else {
            Swal.fire(
                'Template Tidak Tersedia',
                'Tidak ada template yang dapat di akses oleh akun anda',
                'warning'
            )
        }
    })



    $(document).on('click', '.__buat-dokumen-pilih-template', function() {
        let dataID = $(this).data('id')

        $.ajax({
            url: "<?php echo site_url('dokumenpk/get-template/') ?>" + dataID,
            type: 'GET',
            data: {},
            success: (res) => {
                preapreForm_afterChooseTemplate({
                    templateId: dataID,
                    templateTitle: $(this).text(),
                    data: res,
                    target: 'create'
                })
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Gagal',
                    icon: "warning",
                    text: 'Ada Perubahan Data disatker, Coba Lagi !',
                    type: 'confirm',
                    confirmButtonText: 'Refresh Halaman',

                }).then(result => {
                    if (result.value) {
                        location.reload();
                    }
                });
            },
            fail: (xhr) => {
                alert("Terjadi kesalahan pada sistem")

            }
        })
    })



    element_modalFormBackChooseTemplate.on('click', function() {
        prepareForm_reset();
    })



    $(document).on('click', 'input:checkbox[name=form-checkall-row]', function() {
        let rowChild = $('input:checkbox[name=form-check-row]').parents('tr').find('td')

        $('input:checkbox[name=form-check-row]').prop('checked', this.checked);
        let rowid = rowChild.find('.paket').attr('data-rowid');
        if (!this.checked) {


            if (typeof rowid === 'undefined') {
                rowChild.addClass('disabled')
                rowChild.find('input').attr('readonly', 'readonly')
                rowChild.find('input').val('')
            } else {

                rowChild.addClass('disabled')
                rowChild.find('input').attr('readonly', 'readonly')
                rowChild.find('select').attr('disabled', 'disabled')
                rowChild.find('button.paket').attr('disabled', 'true')
                rowChild.find('input').val('')
                rowChild.find('.totalpaket').html("0")

            }
            sessionStorage.clear()
            // var totalPaketElement = $('[data-rowid="' + indikatorId + '"]').find('.totalpaket');
            // totalPaketElement.html(selectedItems.length);



        } else {
            if (typeof rowid === 'undefined') {
                rowChild.removeClass('disabled')
                rowChild.find('input').removeAttr('readonly')
            } else {
                rowChild.removeClass('disabled')

                if (rowChild.find('input[data-pktype]').attr('data-pktype') == "balai") {

                    rowChild.find('input').removeAttr('readonly')
                }
                rowChild.find('select').removeAttr('disabled')
                rowChild.find('button.paket').removeAttr('disabled')
            }



        }
    });



    $(document).on('change', 'input:checkbox[name=form-check-row]', function() {
        let element_checkAll = $('input:checkbox[name=form-checkall-row]'),
            isAllChecked = false,
            element_parentsColumn = $(this).parents('tr').find('td');
        let rowid = element_parentsColumn.find('.paket').attr('data-rowid');

        if (!$(this).is(':checked')) {

            if (typeof rowid === 'undefined') {
                element_parentsColumn.addClass('disabled')
                element_parentsColumn.find('input').attr('readonly', 'readonly')
                element_parentsColumn.find('input').val('')
            } else {
                element_parentsColumn.addClass('disabled')
                element_parentsColumn.find('input').attr('readonly', 'readonly')
                element_parentsColumn.find('select').attr('disabled', 'disabled')
                element_parentsColumn.find('button.paket').attr('disabled', 'true')
                element_parentsColumn.find('input').val('')
                element_parentsColumn.find('.totalpaket').html("0")
            }




            sessionStorage.removeItem(rowid);

            // sessionStorage.clear()
        } else {
            if (typeof rowid === 'undefined') {
                element_parentsColumn.removeClass('disabled')
                element_parentsColumn.find('input').removeAttr('readonly')
            } else {
                element_parentsColumn.removeClass('disabled')

                if (element_parentsColumn.find('input[data-pktype]').attr('data-pktype') == "balai") {
                    element_parentsColumn.find('input').removeAttr('readonly')
                }
                element_parentsColumn.find('select').removeAttr('disabled')
                element_parentsColumn.find('button.paket').removeAttr('disabled')

            }
        }

        if ($('input:checkbox[name=form-check-row]:checked').length == $('input:checkbox[name=form-check-row]').length) {
            isAllChecked = true
        }

        element_checkAll.prop('checked', isAllChecked)
    });



    let timerInput
    $(document).on('keyup', 'input[name=kegiatan-anggaran]', function() {
        clearTimeout(timerInput)

        timerInput = setTimeout(() => {
            let totalAnggaran = 0;
            $('input[name=kegiatan-anggaran]').each((key, element) => {

                totalAnggaran += parseFloat($(element).val().replaceAll(".", '').replaceAll(',', '.'))
            })
            $('input[name=total-anggaran]').val(formatRupiah(totalAnggaran.toString().replaceAll('.', ',')))
        }, 100);
    })



    $(document).on('change', 'select[name=created-tahun]', function() {
        if ($(this).val() == date.getFullYear()) {
            $('._option-month-to-hide').addClass('d-none')
        } else {
            $('._option-month-to-hide').removeClass('d-none')
        }

        if ($(this).data('action-target') == 'create') {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/check-dokumen-same-year-exist/') ?>" + $(this).val() + '/' + $(this).data('template-id'),
                type: 'GET',
                data: {},
                success: (res) => {

                    render_warningDokumenYearRevisoin = ''
                    inputValue_revisionSameYear = 0

                    if (res.dokumenExistSameYear != null) {
                        inputValue_revisionSameYear = 1
                        render_warningDokumenYearRevisoin = `
                            <div class="bg-warning text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Peringatan</h5>
                                <p>membuat dokumen baru akan merevisi dokumen yang telah anda buat sebelumnya</p>
                            </div>
                        `

                        render_prepare_btnSubmitToRevision({
                            dokumenID: res.dokumenExistSameYear.last_dokumen_id,
                            dokumenMasterID: res.dokumenExistSameYear.revision_master_dokumen_id,
                            buttonType: 'warning',
                            buttonText: 'Buat Revisi'
                        });
                    } else {
                        if (res.dokumen_type == 'master-balai' || res.dokumen_type == 'balai') {
                            render_warningDokumenYearRevisoin = `
                                <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                    <h5 class="alert-heading">Informasi</h5>
                                    <p>Dokumen perjanjian kinerja dapat dibuat/diedit setelah seluruh satker menginputkan perjanjian kinerja masing-masing. Daftar satker dapat dilihat di bagian bawah formulir.</p>
                                </div>
                            `
                        }
                        render_reset_btnSubmitToRevision()
                    }

                    $('input[name=revision_same_year]').val(inputValue_revisionSameYear)
                    $('.container-revision-alert').html(render_warningDokumenYearRevisoin)
                    $('.container-revision-alert-bottom').html(render_warningDokumenYearRevisoin)
                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                }
            })
        }
    })




    element_btnSaveDokumen.on('click', function() {
        // CheckConnection().then(result => {
        if (saveDokumenValidation()) {
            let oldButtonText = element_btnSaveDokumen.text()
            element_btnSaveDokumen.addClass('d-none');

            // Simpan referensi ke elemen penyimpanan pesan
            let savingMessageElement = $('<center>menyimpan dokumen</center>');
            element_btnSaveDokumen.parent().append(savingMessageElement);


            $('input[name=total-anggaran]').prop("disabled", false)
            let formData = getFormValue();

            if ($(this).attr('data-dokumen-id')) {
                formData['revision_dokumen_id'] = $(this).data('dokumen-id')
                formData['revision_dokumen_master_id'] = $(this).data('dokumen-master-id')

                Swal.fire({
                    title: "Anda yakin akan mengedit dokumen ini ?",
                    html: `<textarea class="form-control" name="pesan-koreksi-dokumen" rows="10" placeholder="Tulis pesan"></textarea>`,
                    confirmButtonText: "Kirim",
                    cancelButtonText: "Batal",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    onCancel: () => {
                        element_btnSaveDokumen.removeClass('d-none');

                    },
                    preConfirm: () => {
                        const pesanRevisi = $('textarea[name=pesan-koreksi-dokumen]').val();

                        if (!pesanRevisi) {
                            Swal.showValidationMessage('Pesan harus diisi');
                            return false;
                        }

                        formData['revision_message'] = $('textarea[name=pesan-koreksi-dokumen]').val();
                        $.ajax({
                            url: "<?php echo site_url('dokumenpk/create') ?>",
                            type: 'POST',
                            data: formData,
                            success: (res) => {
                                if (res.status) {
                                    location.reload()
                                } else {
                                    Swal.fire(
                                        'Gagal',
                                        res.message,
                                        'error'
                                    ).then(result => {
                                        location.reload()
                                    })
                                }
                            },
                            fail: (xhr) => {
                                alert('Terjadi kesalahan pada sistem')
                                console.log(xhr)
                            }
                        })
                    }
                })
            } else {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/create') ?>",
                    type: 'POST',
                    data: formData,
                    success: (res) => {
                        if (res.status) {
                            location.reload()
                        } else {
                            Swal.fire(
                                'Gagal',
                                res.message,
                                'error'
                            ).then(result => {
                                location.reload()
                            })
                        }
                    },
                    fail: (xhr) => {
                        alert('Terjadi kesalahan pada sistem')
                        console.log(xhr)
                    }
                })
            }

        }
        // }).catch(error => {
        //     alert("Youre internet connection  cs slow");
        // });
    })




    element_btnSaveEditDokumen.on('click', function() {

        let dataID = $(this).data('id')


        Swal.fire({
            title: "Anda yakin akan mengedit dokumen ini ? ",
            html: `<textarea class="form-control" name="pesan-revisi-dokumen" rows="10" placeholder="Tulis pesan" required></textarea>`,
            confirmButtonText: "Kirim, Beri alasan",
            cancelButtonText: "Batal",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            preConfirm: () => {

                const pesanRevisi = $('textarea[name=pesan-revisi-dokumen]').val();

                if (!pesanRevisi) {
                    Swal.showValidationMessage('Pesan harus diisi');
                    return false;
                }
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/change-status') ?>",
                    type: "POST",
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        dokumenType: 'hold-edit',
                        dataID: dataID,
                        message: $('textarea[name=pesan-revisi-dokumen]').val(),
                        newStatus: 'hold'
                    },
                    success: (res) => {
                        // CheckConnection().then(result => {

                        if (saveDokumenValidation()) {

                            let oldButtonText = element_btnSaveEditDokumen.text()
                            element_btnSaveEditDokumen.addClass('d-none')
                            element_btnSaveEditDokumen.parent().append('<center>menyimpan dokumen</center>')

                            let formData = getFormValue(),
                                dataId = $(this).data('id')

                            formData['id'] = $(this).data('id')
                            formData['csrf_test_name'] = res.token


                            $.ajax({
                                url: "<?php echo site_url('dokumenpk/editDokumen') ?>",
                                type: "POST",
                                data: formData,
                                success: (res) => {

                                    if (res.status) {
                                        location.reload()

                                    }
                                },
                                fail: (xhr) => {
                                    alert('Terjadi kesalahan pada sistem')
                                    console.log(xhr)
                                }
                            })
                        }
                        // }).catch(error => {
                        //     alert("Youre internet connection  cs slow");
                        // });
                    }
                })
            }
        })

    })



    $(document).on('click', '.__prepare-revisi-dokumen', function() {
        paramsBtnPaket = "edit";

        let document_id = $(this).data('id')
        element_btnSaveEditDokumen.data('id', document_id)

        prepareRevisiDocument({
            dataId: $(this).data('id'),
            templateId: $(this).data('template-id'),
            beforeModalMount: (res) => {
                //get session satker
                let sessionUserLevelId = res.dokumen.dokumen_type == 'balai' ? res.dokumen.balaiid : res.dokumen.satkerid
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/get-list-template-buat-dokumen') ?>" + "/" + res.dokumen.dokumen_type + "/" + sessionUserLevelId,
                    type: 'GET',
                    success: (res) => {

                    }
                })

                //end 


                render_prepare_btnSubmitToRevision({
                    dokumenID: res.dokumen.id,
                    dokumenMasterID: res.dokumen.revision_master_dokumen_id ?? res.dokumen.id
                });
            }
        })


    })



    $(document).on('click', '.__lihat-dokumen', function() {

        if ($(this).data('type') == "uptBalai-add") {
            paramsBtnPaket = "uptBalai-add";

        } else {

            paramsBtnPaket = "lihat";
        }


        prepareRevisiDocument({
            dataId: $(this).data('id'),
            templateId: $(this).data('template-id'),
            beforeModalMount: () => {
                $('#modalForm').find('.container-revision-alert').addClass('d-none')
                $('#modalForm').find('input').attr('disabled', 'disabled')
                $('#modalForm').find('select').attr('disabled', 'disabled')
                $('#modalForm').find('.modal-footer').addClass('d-none')

                $('#modalForm').find('.__remove-item-kegiatan').addClass('d-none')
                $('#modalForm').find('#__add-item-kegiatan').addClass('d-none')

            }
        })

    })



    $(document).on('click', '.__edit-dokumen', function() {

        if ($(this).data('type') == "uptBalai-add") {
            paramsBtnPaket = "uptBalai-add";

        } else {

            paramsBtnPaket = "edit";
        }
        let documentId = $(this).data('id')

        prepareRevisiDocument({
            dataId: documentId,
            templateId: $(this).data('template-id'),
            beforeModalMount: () => {
                element_btnSaveEditDokumen.data('id', documentId);
                $('.__save-dokumen').addClass('d-none')
                $('.__save-update-dokumen').removeClass('d-none')
                // console.log($('.__save-update-dokumen'))
                $('#modalForm').find('.container-revision-alert').addClass('d-none')
            }
        })
    })



    function prepareRevisiDocument(params = {
        dataId: '',
        templateId: '',
        beforeModalMount: () => {}
    }) {
        const promiseGetTemplate = new Promise((resolve, reject) => {
            var templateId = params.templateId,
                dataId = params.dataId

            $.ajax({
                url: "<?php echo site_url('dokumenpk/get-template/') ?>" + templateId + "/" + dataId,
                type: 'GET',
                success: (res) => {
                    preapreForm_afterChooseTemplate({
                        dataId: dataId,
                        templateId: templateId,
                        data: res,
                        target: 'koreksi'
                    })

                    element_modalFormTitle.html(`
                        <h6>Lihat Dokumen</h6>
                        <small>${ res.template.title }</small>
                    `)

                    resolve({

                        dataId: dataId,
                        templateRows: res.templateRow
                    })



                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                    reject(then)
                }
            })
        })

        promiseGetTemplate.then((res) => {

            getRows = res.templateRows;
            $.ajax({
                url: "<?php echo site_url('dokumenpk/detail/') ?>" + res.dataId,
                type: 'GET',
                success: (res) => {
                    if (res.dokumen.revision_same_year_number != 0) {
                        $('input[name=revision_same_year]').val(1)
                    }


                    satkerIdDefault = 0;
                    res.rows.forEach((data, key) => {
                        let elementInput_target = $('.__inputTemplateRow-target[data-row-id=' + data.template_row_id + ']'),
                            elementInput_target_satuan = $('.select-target-satuan[data-row-id=' + data.template_row_id + ']'),
                            elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id=' + data.template_row_id + ']')

                        // elementInput_target.val(data.target_value)
                        // elementInput_outcome.val(data.outcome_value)
                        elementInput_target.val(
                            (data.template_row_id == "151010") ||
                            (data.template_row_id == "141009") ? data.target_value :
                            formatRupiah(data.target_value.toString().replaceAll('.', ',')))
                        data.target_sat ? elementInput_target_satuan.val(data.target_sat) : ''
                        elementInput_outcome.val(
                            (data.template_row_id == "151010") ||
                            (data.template_row_id == "141009") ? data.outcome_value :
                            formatRupiah(data.outcome_value.toString().replaceAll('.', ',')))


                        const idPaketArray = res.paket
                            .filter(item => item.template_row_id == data.template_row_id)
                            .map(item => {
                                    return {
                                        paketId: item.idpaket,
                                        target_nilai: item.target_value,
                                        target_satuan: item.target_unit,
                                        outcome_nilai: item.output_value,
                                        outcome_satuan: item.output_unit
                                    };
                                }

                            );

                        selectedItems = idPaketArray;

                        sessionStorage.setItem(data.template_row_id, JSON.stringify(idPaketArray));

                        var totalPaketElement = $('[data-rowid="' + data.template_row_id + '"]').find('.totalpaket');

                        //

                        const foundRow = getRows.find(row => row.id === data.template_row_id);
                        if (foundRow) {
                            // Menggunakan nilai 'satkerid' dari 'getRows' jika ditemukan
                            satkerIdDefault = foundRow.satkerid;
                        }

                        var elem = $('[data-rowid="' + data.template_row_id + '"]');
                        elem.attr('data-satkerid', res.dokumen.satkerid || satkerIdDefault);


                        totalPaketElement.html(selectedItems.length);


                        if (data.is_checked == '0') elementInput_target.parents('tr').find('input:checkbox[name=form-check-row]').trigger('click')




                    })


                    $('.__table-kegiatan').find('tbody').html('')
                    let rowTableKegiatan = ''
                    res.kegiatan.forEach((data, key) => {
                        let rowType = data.id == '-' ? 'input' : 'text'
                        rowTableKegiatan += renderFormTemplate_rowKegiatan_item({
                            id: data.id,
                            nama: data.nama,
                            anggaran: formatRupiah(data.anggaran.toString().replaceAll('.', ',')),
                            rowType: rowType
                        })
                    })
                    $('.__table-kegiatan').find('tbody').html(rowTableKegiatan)
                    res.kegiatan.forEach((data, key) => {
                        let elementInput_target = $('tr[data-kegiatan-id=' + (data.id == "?" ? "-" : data.id) + ']').find('input[name=kegiatan-anggaran]')

                        elementInput_target.val(formatRupiah(data.anggaran.toString().replaceAll('.', ',')))
                    })

                    $('input[name=total-anggaran]').val(formatRupiah(res.dokumen.total_anggaran.toString().replaceAll('.', ',')))
                    $('input[name=ttd-pihak1]').val(res.dokumen.pihak1_ttd)
                    $('input[name=ttd-pihak2]').val(res.dokumen.pihak2_ttd)

                    $('.title-ttd-pihak1').text(res.dokumen.pihak1_initial)
                    $('.title-ttd-pihak2').text(res.dokumen.pihak2_initial)

                    if ($('input[name=ttd-pihak2-jabatan]').length) {
                        $('input[name=ttd-pihak2-jabatan]').val(res.dokumen.pihak2_initial)
                    }

                    if (res.dokumen.pihak1_is_plt == '1') $('input:checkbox[name=ttd-pihak1-plt]').prop('checked', true)
                    if (res.dokumen.pihak2_is_plt == '1') $('input:checkbox[name=ttd-pihak2-plt]').prop('checked', true)

                    $('select[name=created-kota]').val(res.dokumen.kota).trigger('change')
                    $('input[name=created-kota-nama]').val(res.dokumen.kota_nama)
                    $('select[name=created-bulan]').val(res.dokumen.bulan).trigger('change')
                    $('select[name=created-day]').val(res.dokumen.tanggal).trigger('change')
                    $('select[name=created-tahun]').val(res.dokumen.tahun_ttd ?? res.dokumen.tahun).trigger('change')

                    if (res.dokumen.revision_message != null) {
                        $('.container-revision-alert').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Pesan !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    }

                    if (res.listRevision.length > 0) {
                        $('.container-list-revision-message').removeClass('d-none')

                        let listRevisionMessage = ''

                        res.listRevision.forEach((data, index) => {
                            if (data.pesan != null) {
                                listRevisionMessage += `
                                    <tr>
                                        <td>${ data.tanggal }</td>
                                        <td>${ data.pesan }</td>
                                        <td>${ data.koreksi_by }</td>
                                    </tr>
                                `
                            }
                        });

                        $('.container-list-revision-message').find('tbody').html(listRevisionMessage);
                    }

                    params.beforeModalMount(res)


                    $('.modal .btn-modal-full').trigger('click');
                    $('#modalForm').modal('show')
                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                }
            })
        })
    }



    function setDetailDataInForm(dokumenId) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/detail/') ?>" + dokumenId,
            type: 'GET',
            success: (res) => {

                getRows = res.rows;

                satkerIdDefault = 0;
                res.rows.forEach((data, key) => {
                    let elementInput_target = $('.__inputTemplateRow-target[data-row-id=' + data.template_row_id + ']'),
                        elementInput_target_satuan = $('.select-target-satuan[data-row-id=' + data.template_row_id + ']'),
                        elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id=' + data.template_row_id + ']')

                    elementInput_target.val(
                        (data.template_row_id == "151010") ||
                        (data.template_row_id == "141009") ? data.target_value :
                        formatRupiah(data.target_value.toString().replaceAll('.', ',')))
                    elementInput_target_satuan.val(data.target_sat)
                    elementInput_outcome.val(
                        (data.template_row_id == "151010") ||
                        (data.template_row_id == "141009") ? data.outcome_value :
                        formatRupiah(data.outcome_value.toString().replaceAll('.', ',')))


                    const idPaketArray = res.paket
                        .filter(item => item.template_row_id == data.template_row_id)
                        .map(item => {
                                return {
                                    paketId: item.idpaket,
                                    target_nilai: item.target_value, // Isi dengan nilai target_nilai yang sesuai
                                    target_satuan: item.target_unit, // Isi dengan nilai target_satuan yang sesuai
                                    outcome_nilai: item.output_value, // Isi dengan nilai outcome_nilai yang sesuai
                                    outcome_satuan: item.output_unit // Isi dengan nilai outcome_satuan yang sesuai
                                };
                            }

                        );

                    selectedItems = idPaketArray;

                    sessionStorage.setItem(data.template_row_id, JSON.stringify(idPaketArray));

                    var totalPaketElement = $('[data-rowid="' + data.template_row_id + '"]').find('.totalpaket');

                    //

                    const foundRow = getRows.find(row => row.id === data.template_row_id);
                    if (foundRow) {
                        // Menggunakan nilai 'satkerid' dari 'getRows' jika ditemukan
                        satkerIdDefault = foundRow.satkerid;
                    }

                    var elem = $('[data-rowid="' + data.template_row_id + '"]');
                    elem.attr('data-satkerid', res.dokumen.satkerid || satkerIdDefault);


                    totalPaketElement.html(selectedItems.length);



                    if (data.is_checked == '0') elementInput_target.parents('tr').find('input:checkbox[name=form-check-row]').trigger('click')
                })

                $('.__table-kegiatan').find('tbody').html('')
                let rowTableKegiatan = ''
                // console.log(res.kegiatan)
                res.kegiatan.forEach((data, key) => {
                    let rowType = data.id == '-' ? 'input' : 'text'

                    rowTableKegiatan += renderFormTemplate_rowKegiatan_item({
                        id: data.id,
                        nama: data.nama,
                        anggaran: data.anggaran,
                        rowType: rowType
                    })
                })
                $('.__table-kegiatan').find('tbody').html(rowTableKegiatan)
                res.kegiatan.forEach((data, key) => {
                    let elementInput_target = $('tr[data-kegiatan-id=' + (data.id == "?" ? "-" : data.id) + ']').find('input[name=kegiatan-anggaran]')
                    elementInput_target.val(formatRupiah(data.anggaran.toString().replaceAll('.', ',')))
                })

                $('input[name=total-anggaran]').val(formatRupiah(res.dokumen.total_anggaran.toString().replaceAll('.', ',')))
                $('input[name=ttd-pihak1]').val(res.dokumen.pihak1_ttd)
                $('input[name=ttd-pihak2]').val(res.dokumen.pihak2_ttd)

                $('.title-ttd-pihak1').text(res.dokumen.pihak1_initial)
                $('.title-ttd-pihak2').text(res.dokumen.pihak2_initial)

                if ($('input[name=ttd-pihak2-jabatan]').length) {
                    $('input[name=ttd-pihak2-jabatan]').val(res.dokumen.pihak2_initial)
                }

                if (res.dokumen.pihak1_is_plt == '1') $('input:checkbox[name=ttd-pihak1-plt]').prop('checked', true)
                if (res.dokumen.pihak2_is_plt == '1') $('input:checkbox[name=ttd-pihak2-plt]').prop('checked', true)

                $('select[name=created-kota]').val(res.dokumen.kota).trigger('change')
                $('input[name=created-kota-nama]').val(res.dokumen.kota_nama)
                $('select[name=created-bulan]').val(res.dokumen.bulan).trigger('change')
                $('select[name=created-tahun]').val(res.dokumen.tahun_ttd ?? res.dokumen.tahun).trigger('change')
            },
            fail: (xhr) => {
                alert("Terjadi kesalahan pada sistem")
                console.log(xhr)
            }
        })
    }



    $(document).on('click', '.__cetak-dokumen', function() {
        let dokumenMasterID = $(this).data('dokumen-master-id')

        if ($(this).data('number-revisioned')) {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/get-list-revisioned/') ?>" + dokumenMasterID,
                type: 'GET',
                success: (res) => {
                    let list = ''
                    if ($(this).data('select-top')) {
                        cetakDokumen(res.dokumenList[0].id, true)
                    } else {
                        res.dokumenList.forEach((data, key) => {
                            let listTitle = 'Dokumen Awal',
                                activeClass = '',
                                activeSubTitle = ''

                            if (data.revision_master_number) listTitle = 'Koreksi #' + data.revision_number

                            if (data.status == 'setuju') {
                                activeClass = 'active bg-success border-success'
                                activeSubTitle = '<div><small>Telah di setujui</small></div>'
                            }

                            if (data.is_revision_same_year == '1') {
                                listTitle = 'Revisi'
                                activeClass = 'active bg-danger border-danger'
                            }

                            list += `
                                <button 
                                    class="list-group-item list-group-item-action ${activeClass}"
                                    onclick="cetakDokumen(${data.id}, true)"
                                >
                                    ${listTitle}
                                    ${activeSubTitle}
                                </button>
                            `
                        })

                        $('#modal-cetak-dokumen-revisioned').find('.list-group').html(list)
                        $('#modal-cetak-dokumen-revisioned').modal('show')
                    }
                }
            })
        } else {
            cetakDokumen(dokumenMasterID, true)
        }
    })



    $(document).on('click', '.__list-satker-telah-membuat-dokumen', function() {
        $('#modalSatkerListCreated').modal('show')
        $.ajax({
            url: "<?php echo site_url('dokumenpk/list-satker-balai') ?>",
            type: 'GET',
            success: (res) => {
                let renderList = ''
                res.data.forEach(data => {
                    // let renderCheck = ''

                    // if (data.iscreatedPK > 0) {
                    //     renderCheck = '<i class="fas fa-check"></i>'
                    // } else if (data.iscreatedPKBeforeAcc > 0) {
                    //     renderCheck = '<div class="d-flex justify-content-between align-items-center"><span class = "badge badge-pill px-3 font-weight-bold ' + data.status_color + '"> ' + data.status_now + ' </span> <div > ';
                    // }
                    // renderList += `
                    //     <li class="list-group-item d-flex justify-content-between">
                    //         <label>${data.satker}</label>
                    //         ${renderCheck}
                    //     </li>
                    // `
                    let renderCheck = '<i class="fas fa-times" style="color: red;"></i>'
                    if (data.satkerCheck == 'setuju') {

                        renderCheck = '<i class="fas fa-check"></i>'

                    }
                    if (data.satkerCheck == 'hold' || data.satkerCheck == 'tolak') {
                        renderCheck = '<div class="d-flex justify-content-between align-items-center"><span class = "badge badge-pill px-3 font-weight-bold bg-secondary">Pending</span> <div > ';
                    }
                    renderList += `<li class="list-group-item d-flex justify-content-between">
                            <label>${data.satker}</label>
                            ${renderCheck}
                        </li>`;

                });



                $('#modalSatkerListCreated').find('.list-group').html(renderList)
            }
        })
    })



    $(document).on('click', '.__tolak-dokumen', function() {
        let dataID = $(this).data('id')

        Swal.fire({
            title: "Kenapa dokumen ini di tolak?",
            html: `<textarea class="form-control" name="pesan-tolak-dokumen" rows="10" placeholder="Tulis pesan untuk pembuat dokumen"></textarea>`,
            confirmButtonText: "Kirim, dan Tolak Dokumen",
            cancelButtonText: "Batal",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            preConfirm: () => {
                $.ajax({
                    url: "<?php echo site_url('dokumenpk/change-status') ?>",
                    type: "POST",
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        dokumenType: 'satker',
                        dataID: dataID,
                        message: $('textarea[name=pesan-tolak-dokumen]').val(),
                        newStatus: 'tolak'
                    },
                    success: (res) => {
                        return res
                    }
                })
            }
        }).then((res) => {
            if (res.value) {
                location.reload()
            }
        })
    })



    $(document).on('click', '.__setujui-dokumen', function() {
        let dataID = $(this).data('id')
        $.ajax({
            url: "<?php echo site_url('dokumenpk/change-status') ?>",
            type: "POST",
            data: {
                csrf_test_name: $('input[name=csrf_test_name]').val(),
                dokumenType: 'satker',
                dataID: dataID,
                newStatus: 'setuju'
            },
            success: (res) => {
                location.reload()
            }
        })
    })



    $(document).on('click', '#__add-item-kegiatan', function() {
        let element_kegiatanTable = $('.__table-kegiatan').find('tbody'),
            element_rowItem_kegiatanTable = $('.__table-kegiatan').find('tbody').find('tr'),
            element_rowItem_anggaran_kegiatan = $('.__table-kegiatan').find('tbody tr').last().find("input[name='kegiatan-anggaran']").val(),
            element_rowItem_nama_kegiatan_fill = $('.__table-kegiatan').find('tbody tr').last().attr("data-kegiatan-nama"),
            element_rowItem_nama_kegiatan = $('.__table-kegiatan').find('tbody tr').last().find("select").val(),
            kegiatan = []

        info = $(this).data("info");

        if (element_rowItem_anggaran_kegiatan != undefined) {



            if (element_rowItem_nama_kegiatan_fill == '-') {
                if (element_rowItem_nama_kegiatan == 0 || element_rowItem_nama_kegiatan == null || element_rowItem_nama_kegiatan == '') {

                    Swal.fire(
                        'Peringatan',
                        'Nama Kegiatan belum terisi',
                        'warning'
                    )
                    return false
                }

                if (element_rowItem_anggaran_kegiatan == 0 || element_rowItem_anggaran_kegiatan == null || element_rowItem_anggaran_kegiatan == '') {

                    Swal.fire(
                        'Peringatan',
                        'Anggaran Kegiatan belum terisi',
                        'warning'
                    )
                    return false
                }

            }


        }


        element_kegiatanTable.append(renderFormTemplate_rowKegiatan_item({
            id: '-',
            nama: '-',
            rowType: 'input'
        }))

        element_rowItem_kegiatanTable.each((key, element) => {
            let idKegiatan = $(element).data('kegiatan-id'),
                namaKegiatan = idKegiatan == '-' ? $(element).find('.__nama-kegiatan-manual').val() : $(element).data('kegiatan-nama');

            if (key < element_rowItem_kegiatanTable.length) kegiatan.push(namaKegiatan)
        })

        $('select.select2').select2({
            ajax: {
                url: "<?php echo site_url('dokumenpk/get-tgiat-for-formpk?exists=') ?>" + JSON.stringify(kegiatan) + "&info=" + info + "&_=" + new Date().getTime(),
                dataType: 'json',
                cache: true
            }
        })
    })



    $(document).on('click', '.__remove-item-kegiatan', function() {
        $(this).parents('tr').remove()
        let timerInput

        clearTimeout(timerInput)

        timerInput = setTimeout(() => {
            let totalAnggaran = 0;
            $('input[name=kegiatan-anggaran]').each((key, element) => {

                totalAnggaran += parseFloat($(element).val().replaceAll(".", '').replaceAll(',', '.'))
            })
            $('input[name=total-anggaran]').val(formatRupiah(totalAnggaran.toString().replaceAll('.', ',')))
        }, 100);

    })



    function getFormValue() {
        let rows = [],
            paket = [],
            kegiatan = []

        $('.__inputTemplateRow-target').each((key, element) => {
            let elementInput_target = $(element),
                elementInput_target_satuan = $('.select-target-satuan').eq(key),
                elementInput_outcome = $('.__inputTemplateRow-outcome').eq(key),
                element_checkRow = $('input:checkbox[name=form-check-row]').eq(key)

            rows.push({
                id: elementInput_target.data('row-id'),
                target: elementInput_target.val().replace('.', ''),
                target_satuan: elementInput_target_satuan.val(),
                outcome: elementInput_outcome.val().replace('.', ''),
                isChecked: element_checkRow.is(':checked') ? '1' : '0'
            })

            paket.push({
                id: elementInput_target.data('row-id'),
                paketId: sessionStorage.getItem(elementInput_target.data('row-id')),
                isChecked: element_checkRow.is(':checked') ? '1' : '0'
            });
        })

        $('.__table-kegiatan').find('tbody').find('tr').each((key, element) => {
            let idKegiatan = $(element).data('kegiatan-id'),
                namaKegiatan = idKegiatan == '-' ? $(element).find('.__nama-kegiatan-manual').val() : $(element).data('kegiatan-nama');

            kegiatan.push({
                id: idKegiatan,
                nama: namaKegiatan,
                anggaran: $(element).find('input[name=kegiatan-anggaran]').val()
            })
        })

        let inputValue = {
            csrf_test_name: $('input[name=csrf_test_name]').val(),
            revisionSameYear: $('input[name=revision_same_year]').val(),
            templateID: element_btnSaveDokumen.data('template-id'),
            rows: rows,
            paket: paket,
            kegiatan: kegiatan,
            totalAnggaran: $('input[name=total-anggaran]').val(),
            ttdPihak1: $('input[name=ttd-pihak1]').val(),
            ttdPihak1_isPlt: $('input:checkbox[name=ttd-pihak1-plt]').is(':checked') ? '1' : '0',
            ttdPihak2: $('input[name=ttd-pihak2]').val(),
            ttdPihak2_isPlt: $('input:checkbox[name=ttd-pihak2-plt]').is(':checked') ? '1' : '0',
            kota: $('select[name=created-kota]').val(),
            kotaNama: $('input[name=created-kota-nama]').val(),
            bulan: $('select[name=created-bulan]').val(),
            tanggal: $('select[name=created-day]').val(),
            tahun: $('select[name=created-tahun]').val()
        }
        if ($('input[name=ttd-pihak2-jabatan]').length) inputValue.ttdPihak2Jabatan = $('input[name=ttd-pihak2-jabatan]').val()

        return inputValue
    }



    function saveDokumenValidation() {
        let checkInputKegiatanAnggatan = true,
            checkInputKegiatanManual = true,
            checkInputTarget = true,
            checkInputOutcome = true,
            checkPaket = true

        $('.paket').each((index, element) => {

            let element_rowParent = $(element).parents('tr').find('td'),
                checlist = element_rowParent.find('input:checkbox[name=form-check-row]').is(':checked')

            if (checlist) {
                if ($(element).find('.totalpaket').text() > 0) {
                    checkPaket = true
                } else {
                    checkPaket = false
                }
            }

        })

        $('.__inputTemplateRow-target').each((index, element) => {
            let element_rowParent = $(element).parents('tr').find('td'),
                checlist = element_rowParent.find('input:checkbox[name=form-check-row]').is(':checked')

            if (checlist) {
                if ($(element).val() != '' && checkInputTarget == true) {
                    checkInputTarget = true
                } else {
                    checkInputTarget = false
                }
            }
        })

        $('.__inputTemplateRow-outcome').each((index, element) => {
            let element_rowParent = $(element).parents('tr').find('td'),
                checlist = element_rowParent.find('input:checkbox[name=form-check-row]').is(':checked')

            if (checlist) {
                if (!$(element).parents('td').hasClass('d-none')) {
                    if ($(element).val() != '' && checkInputOutcome == true) {
                        checkInputOutcome = true
                    } else {
                        checkInputOutcome = false
                    }
                }
            }
        })





        $('input[name=kegiatan-anggaran]').each((index, element) => {
            if ($(element).val().replaceAll(".", '').replaceAll(',', '.') > 0 && checkInputKegiatanAnggatan == true) {
                checkInputKegiatanAnggatan = true
            } else {
                checkInputKegiatanAnggatan = false
            }
        })

        $('.__nama-kegiatan-manual').each((index, element) => {
            if ($(element).val() != null && checkInputKegiatanAnggatan == true) {
                checkInputKegiatanManual = true
            } else {
                checkInputKegiatanManual = false
            }
        })

        if (checkPaket == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat paket yang belum dipilih pada indikator',
                'warning'
            )
            return false
        }

        if (checkInputTarget == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat target yang belum terisi',
                'warning'
            )
            return false
        }

        if (checkInputOutcome == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat outcome yang belum terisi',
                'warning'
            )
            return false
        }
        if ($('input[name=kegiatan-anggaran]').length < 1) {
            Swal.fire(
                'Peringatan',
                'Daftar Dan Anggaran Kegiatan Belum Ada',
                'warning'
            )
            return false

        }



        if (checkInputKegiatanManual == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat nilai kegiatan yang belum terisi',
                'warning'
            )
            return false
        }

        if (checkInputKegiatanAnggatan == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat angaran untuk kegiatan yang belum terisi',
                'warning'
            )
            return false
        }

        if ($('input[name=total-anggaran]').val() == '') {
            Swal.fire(
                'Peringatan',
                'Total Anggaran belum terisi',
                'warning'
            )
            return false
        }

        if ($('input[name=ttd-pihak1]').val() == '') {
            Swal.fire(
                'Peringatan',
                'Penandatangan pihak pertama belum terisi',
                'warning'
            )
            return false
        }

        if ($('input[name=ttd-pihak2]').val() == '') {
            Swal.fire(
                'Peringatan',
                'Penandatangan pihak kedua belum terisi',
                'warning'
            )
            return false
        }

        if ($('select[name=created-bulan]').val() == '') {
            Swal.fire(
                'Peringatan',
                'Bulan dokumen belum di pilih',
                'warning'
            )
            return false
        }

        return true
    }



    function prepareForm_reset() {
        $('#modalForm').find('input').removeAttr('disabled')
        $('#modalForm').find('select').removeAttr('disabled')
        $('#modalForm').find('.modal-footer').removeClass('d-none')

        $('.container-revision-alert-bottom').html('')

        element_modalDialog.removeClass('modal-xl')
        element_modalFooter.addClass('d-none')
        element_modalFormMakeDokumen.addClass('d-none')
        element_modalFormChooseTemplate.removeClass('d-none')
        element_modalFormBackChooseTemplate.addClass('d-none')
        element_modalFormTitle.html('Pilih Dokumen')
        render_reset_btnSubmitToRevision()
        sessionStorage.clear();
    }



    function cetakDokumen(_dokumenID, _toConfirm) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/export-pdf/') ?>" + _dokumenID + "?_=" + new Date().getTime(),
            type: 'GET',
            cache: false,
            success: (res) => {
                $('#modal-cetak-dokumen-revisioned').modal('hide')

                setTimeout(() => {
                    let element_iframePreviewDokumen = element_modalPreviewCetakDokumen.find('iframe')

                    if (res.dokumen.revision_message != null) {
                        element_iframePreviewDokumen.css({
                            // 'height': '60vh'
                            'height': '100vh'
                        })
                        $('.container-revision-alert-cetak').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Pesan !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    } else {
                        element_iframePreviewDokumen.css({
                            // 'height': '80vh'
                            'height': '100vh'
                        })
                        $('.container-revision-alert-cetak').html('')
                    }


                    element_iframePreviewDokumen.attr('src', '/api/showpdf/tampilkan/' + _dokumenID + '?preview=true&_=' + Math.round(Math.random() * 10000000))

                    element_modalPreviewCetakDokumen.modal('show')

                    if (_toConfirm) {
                        if (res.dokumen.status != 'setuju' && res.dokumen.status != 'tolak') {
                            element_modalPreviewCetakDokumen.find('.modal-footer').html(`
                                <div class="p-2">
                                    <button class="btn btn-sm btn-outline-danger mr-2 __tolak-dokumen" data-id="${_dokumenID}">
                                        <i class="fa fa-ban"></i> Tolak
                                    </button>

                                    <button class="btn btn-sm btn-success __setujui-dokumen" data-id="${_dokumenID}">
                                        <i class="fa fa-check"></i> Setujui
                                    </button>
                                </div>
                            `)
                        } else {
                            element_modalPreviewCetakDokumen.find('.modal-footer').empty()
                        }
                    } else {
                        element_modalPreviewCetakDokumen.find('.modal-footer').empty()
                    }
                }, 400)
                // $('.btn-modal-full').trigger('click')
            }
        })
    }



    function preapreForm_afterChooseTemplate(params = {
        dataId: '',
        templateId: '',
        templateTitle: '',
        data: {},
        target: ''
    }) {

        element_btnSaveDokumen.attr('data-template-id', params.templateId)
        element_modalDialog.addClass('modal-xl')
        element_modalFooter.removeClass('d-none')
        element_modalFormChooseTemplate.addClass('d-none')
        element_modalFormMakeDokumen.removeClass('d-none')
        element_modalFormBackChooseTemplate.removeClass('d-none')

        if (params.hasOwnProperty('templateTitle')) {
            element_modalFormTitle.html(`
                <h6>Perjanjian Kinerja Tahun <?php echo $sessionYear ?></h6>
                <small>${params.templateTitle}</small>
            `)
        }

        renderFormTemplate(params.dataId, params.data, params.target)
        // $('select[name=created-tahun]').val(<?php echo $sessionYear ?>).trigger('change')

        if (params.data.balaiValidasiSatker.valudasiCreatedDokumen == false) {
            // $('#modalForm').find('.container-revision-alert').addClass('d-none')

            if (params.data.template.type == 'master-balai' || params.data.template.type == 'balai') {
                $('#modalForm').find('input').attr('disabled', 'disabled')
                $('#modalForm').find('select').attr('disabled', 'disabled')
                $('#modalForm').find('.modal-footer').addClass('d-none')
            }

            let renderCheckListSatkerBalai = ''
            params.data.balaiValidasiSatker.balaiChecklistSatker.forEach((data, index) => {

                // if (data.iscreatedPK > 0) renderCheck = '<i class="fas fa-check mt-2"></i>'

                // renderCheckListSatkerBalai += `
                //     <li class="list-group-item d-flex justify-content-between">
                //         <label>${ data.satker }</label>
                //         ${renderCheck}
                //     </li>
                // `

                let renderCheck = '<i class="fas fa-times" style="color: red;"></i>'
                if (data.satkerCheck == 'setuju') {

                    renderCheck = '<i class="fas fa-check"></i>'

                }
                if (data.satkerCheck == 'hold' || data.satkerCheck == 'tolak') {
                    renderCheck = '<div class="d-flex justify-content-between align-items-center"><span class = "badge badge-pill px-3 font-weight-bold bg-secondary">Pending</span> <div > ';
                }
                renderCheckListSatkerBalai += `<li class="list-group-item d-flex justify-content-between">
                            <label>${data.satker}</label>
                            ${renderCheck}
                        </li>`;
            });


            if (params.data.template.type == 'master-balai' || params.data.template.type == 'balai') {
                $('.container-revision-alert').append(`
                    <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                        <h5 class="alert-heading">Informasi</h5>
                        <p>Dokumen perjanjian kinerja dapat dibuat/diedit setelah seluruh satker menginputkan perjanjian kinerja masing-masing. Daftar satker dapat dilihat di bagian bawah formulir.</p>
                    </div>
                `)

                $('.container-revision-alert-bottom').html(`
                    <h6 class="mb-4 mt-4">Daftar satker yang telah membuat dokumen</h6>
                    <div class="list-group">
                        ${renderCheckListSatkerBalai}
                    </div>
                `)
            }
        } else {
            if (params.data.template.type == 'master-balai' || params.data.template.type == 'balai') {
                $('.container-revision-alert').append(`
                    <div class="bg-success text-white pt-3 pr-3 pb-3 pl-3" role="alert">
                        <i class="fas fa-check mr-3"></i> <strong>Semua Satker Telah Menginput Dokumen Perjanjian Kinerja. Silahkan Input Perjanjian Kinerja</strong>
                    </div>
                `)
            }
        }
    }



    function render_prepare_btnSubmitToRevision(params = {
        dokumenID: '',
        dokumenMasterID: '',
        buttonType: '',
        buttonText: ''
    }) {
        let buttonType = params.hasOwnProperty('buttonType') ? 'btn-' + params.buttonType : 'btn-danger',
            buttonText = params.hasOwnProperty('buttonText') ? params.buttonText : 'Simpan Koreksi'

        element_btnSaveDokumen.attr('data-dokumen-id', params.dokumenID)
        element_btnSaveDokumen.attr('data-dokumen-master-id', params.dokumenMasterID)

        element_btnSaveDokumen.addClass(buttonType)
        element_btnSaveDokumen.text(buttonText)
    }



    function render_reset_btnSubmitToRevision() {
        element_btnSaveDokumen.removeAttr('data-dokumen-id')
        element_btnSaveDokumen.removeAttr('data-dokumen-master-id')

        element_btnSaveDokumen.removeClass('btn-danger')
        element_btnSaveDokumen.removeClass('btn-warning')
        element_btnSaveDokumen.text('Simpan Dokumen')
    }



    function renderFormTemplate(_dataId, _data, _target) {
        last_dokumen_id = '';
        if (_target == 'create' && _data.dokumenExistSameYear != null) {
            paramsBtnPaket = "edit";
            last_dokumen_id = _data.dokumenExistSameYear.last_dokumen_id;
        }

        let template = _data.template,
            value_dataId = _dataId ?? last_dokumen_id,
            templateExtraData = _data.templateExtraData,
            render_rowsForm = renderFormTemplate_rowTable(_data.templateRow, _data.template.type, _data.satkerid, value_dataId, _data.tahun_dokumen),
            render_rowKegiatan = renderFormTemplate_rowKegiatan(_data.templateKegiatan),
            render_listInfo = renderFormTemplate_listInfo(_data.templateInfo),
            render_ttdPihak2 = renderFormTemplate_ttdPihak2(_data.penandatangan.pihak2, templateExtraData.jabatanPihak2),
            render_opsiKota = renderFormTemplate_opsiKota(_data.kota),
            render_opsiBulan = renderFormTemplate_opsiBulan(_data.bulan),
            render_opsiTahun = renderFormTemplate_opsiTahun(_data.tahun),
            render_warningDokumenYearRevisoin = '',
            inputValue_revisionSameYear = 0,
            classDNoneOutcome = '',
            titleTheadTable = '',
            theadBalaiTarget = '',
            theadBalaiTargetNumber = '',
            titleTheadTableOutcomeBalai = ''


        if (_target == 'create' && _data.dokumenExistSameYear != null) {
            render_warningDokumenYearRevisoin = `
                <div class="bg-warning text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                    <h5 class="alert-heading">Peringatan</h5>
                    <p>membuat dokumen baru akan merevisi dokumen yang telah anda buat sebelumnya</p>
                </div>
            `
            inputValue_revisionSameYear = 1

            render_prepare_btnSubmitToRevision({
                dokumenID: _data.dokumenExistSameYear.last_dokumen_id,
                dokumenMasterID: _data.dokumenExistSameYear.revision_master_dokumen_id,
                buttonType: 'warning',
                buttonText: 'Buat Revisi'
            });

            setDetailDataInForm(_data.dokumenExistSameYear.last_dokumen_id)
        }

        if (_data && _data.dokumenExistSameYear && _data.dokumenExistSameYear.is_revision_same_year !== undefined) {
            if (_data.dokumenExistSameYear.is_revision_same_year != 0) {
                inputValue_revisionSameYear = 1;
            }
        }






        if (
            _data.template.type == 'eselon1' ||
            _data.template.type == 'eselon2' ||
            _data.template.type == 'master-balai'
        ) {
            classDNoneOutcome = 'd-none'
        }

        if (_data.template.type == 'master-balai' || _data.template.type == 'eselon1') {
            titleTheadTable = '';
            titleTheadTableOutcomeBalai = '';
            theadBalaiTargetNumber = '';
            theadBalaiTargetNumber += '<td class="text-center p-2">(3)</td>';
            switch (_data.template.type) {
                case 'master-balai':
                    titleTheadTableOutcomeBalai = '<td class="text-center" style="width: 10%">Target Satker</td>';
                    titleTheadTable = '<td class="text-center" style="width: 10%">Outcome Satker</td>';
                    theadBalaiTargetNumber += '<td class="text-center p-2">(4)</td>';
                    break;

                case 'eselon1':
                    titleTheadTable = 'Acuan';
                    break;

                default:
                    titleTheadTable = '';
                    titleTheadTableOutcomeBalai = '';

                    break;
            }

            theadBalaiTarget = '<td class="text-center" style="width: 15%">Target ' + <?php echo $sessionYear ?> + '</td>';
        } else {
            titleTheadTable = '<td class="text-center" style="width: 15%">Target ' + <?php echo $sessionYear ?> + '</td>';
        }


        let render = `
            <input type="hidden" name="revision_same_year" value="${inputValue_revisionSameYear}" />

            <div class="container-revision-alert">
                ${render_warningDokumenYearRevisoin}
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr class="sticky-header-1">
                        <td class="text-center"  style="width: 70%" colspan="3">Sasaran Program / Sasaran Kegiatan / Indikator</td>
                        ${titleTheadTableOutcomeBalai}
                        ${titleTheadTable}
                  
                        ${theadBalaiTarget}
                        <td class="text-center ${classDNoneOutcome}" style="width: 15%">
                            Outcome
                        </td>
                    </tr>
                    <tr style="font-size:10px" class="sticky-header-2">
                        <td class="text-center p-2 align-middle">
                            <input type="checkbox" name="form-checkall-row" checked />
                        </td>
                        <td class="text-center p-2" colspan="2">(1)</td>
                        <td class="text-center p-2">(2)</td>
                        ${theadBalaiTargetNumber}
                        <td class="text-center p-2 ${classDNoneOutcome}">(3)</td>
                    </tr>
                </thead>
                <tbody>
                    ${ render_rowsForm }
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-7">
                    <div class="mt-4">
                        <h6>${ template.keterangan != '' ? 'KETERANGAN' : '' }</h6>
                        <div>${ template.keterangan }</div>
                    </div>
                    <div class="mt-5 mb-5">
                        <h6>${ template.info_title }</h6>
                        <!--
                        <ul class="list-group">
                            ${ render_listInfo }
                        </ul>
                        -->
                        <table class="table table-striped border __table-kegiatan">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama ${ capitalizeFirstLetter(template.info_title) }</th>
                                    <th class="text-center" width="250px">Nominal Anggaran</th>
                                    <th width="50px">
                                        <button class="btn btn-sm btn-primary" id="__add-item-kegiatan" title="Tambah Kegiatan & Anggaran" data-info = "${template.info_title}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                ${render_rowKegiatan}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="align-middle"> <strong>Total Anggaran</strong></td>
                                    <td class="align-middle"> 
                                        <div class="input-group mt-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp. </span>
                                            </div>
                                            <input 
                                                type="text" 
                                                id="total-anggaran"
                                                class="form-control" 
                                                style="background: #F7F8FA ;text-align: right;" 
                                                name="total-anggaran" 
                                                placeholder="Nominal Total Anggaran" disabled
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-4 pt-4">
                        <h6 class="mb-4">Dokumen Dibuat Di</h6>
                        <div class="form-group row d-none">
                            <label class="col-sm-2 col-form-label">Kota</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="created-kota">
                                    ${render_opsiKota}
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kota</label>
                            <div class="col-sm-5">
                                <input class="form-control"  style="background: #F7F8FA" name="created-kota-nama" value="${ templateExtraData.kotaNama }" readonly />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Bulan</label>
                            <div class="col-sm-5">
                                <select class="form-control opsi-bulan" name="created-bulan">
                                    ${render_opsiBulan}
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="created-day">
                                <option value="">Pilih Tanggal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tahun</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="created-tahun" data-template-id="${template.id}" data-action-target="${_target}" style="background:#F7F8FA" readonly>
                                    ${render_opsiTahun}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                   
                    <div class="form-group mt-4 pt-4">
                        <div class="d-flex justify-content-between">
                            <label>
                                <strong>Pihak Pertama</strong>
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" name="ttd-pihak1-plt" type="checkbox" value="1" id="ttdPihak1Plt">
                                <label class="form-check-label" for="ttdPihak1Plt">
                                    Plt. (Dipilih Jika Pejabat Plt)
                                </label>
                            </div>
                        </div>
                        <div>
                            <small class="title-ttd-pihak1">
                                ${_data.penandatangan.pihak1}
                            </small>
                        </div>
                        <input class="form-control" name="ttd-pihak1" placeholder="Masukkan Nama Penanda Tangan" required  onkeyup="this.value = this.value.toUpperCase();" onkeypress="return inputHarusHurufDenganTitik(event)"  />
                        <small style="color: #fc0758; font-weight: bold">
                            <i>Isi nama tanpa gelar</i>
                        </small>
                    </div>
                    <div class="form-group mt-4 pt-2">
                        <div class="d-flex justify-content-between">
                            <label>
                                <strong>Pihak Kedua</strong>
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" name="ttd-pihak2-plt" type="checkbox" value="1" id="ttdPihak2Plt">
                                <label class="form-check-label" for="ttdPihak2Plt">
                                    Plt. (Dipilih Jika Pejabat Plt)
                                </label>
                            </div>
                        </div>
                        <div>
                            ${render_ttdPihak2}
                            <small style="color: #fc0758; font-weight: bold">
                                <i>Isi nama tanpa gelar</i>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-revision-alert-bottom">
            </div>


            <div class="container-list-revision-message d-none pt-5">
                <div class="mt-5">
                    <div class="row">
                        <div class="col-sm-10">
                            <h5 style="color: #000">Daftar Koreksi</h5>
                        </div>
                        <div class="col-sm-2">
                            <a href="<?php echo base_url('dokumenpk-download-log') ?>/` + `${value_dataId}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> Download Log</a>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <th width="300px">Tanggal</th>
                            <th>Pesan Koreksi</th>
                            <th>Koreksi Oleh</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        `

        $('#make-dokumen').html(render)

        var numberMask = IMask(document.getElementById('total-anggaran'), {
            mask: Number,
            thousandsSeparator: '.'
        });

        $('select.select2').select2();
    }



    function renderFormTemplate_rowTable(_data, _templateType, _satkerId, DocID, _tahun) {


        let rows = '',
            rowNumber = 1,
            colspanSectionTitle = 3,
            classDNoneOutcome = '',
            data_value = ''

        if (
            _templateType == 'eselon1' ||
            _templateType == 'eselon2' ||
            _templateType == 'master-balai'
        ) {
            colspanSectionTitle = 2
            classDNoneOutcome = 'd-none'
        }


        _data.forEach((data, key) => {
            switch (data.type) {
                case 'section_title':
                    rowNumber = 1
                    if (data.prefix_title == 'full') {
                        rows += `
                            <tr>
                                <td colspan="${colspanSectionTitle + 2}">
                                    <strong>${ data.title }</strong>
                                </td>
                            </tr>
                        `
                    } else {
                        rows += `
                            <tr>
                                <td>
                                    <!-- <input type="checkbox" name="form-check-row" checked style="margin-left: 8px !important" /> -->
                                </td>
                                <td>
                                    <strong>${ data.prefix_title ?? '-' }</strong>
                                </td>
                                <td colspan="${colspanSectionTitle}">
                                    <strong>${ data.title }</strong>
                                </td>
                            </tr>
                        `
                    }

                    break;

                case 'form':
                    let renderInputTarget = '';


                    if (_templateType == 'master-balai' || _templateType == 'eselon1') {


                        renderInputTarget = `

                            <td>
                                <div class="input-group mr-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text">${formatRupiah(data.targetSatkerValue.toString().replaceAll('.',','))}</span>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                        ${ data.outcome_satuan.split(';')[0]}
                                     </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group mr-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ formatRupiah(data.outcomeSatkerValue.toString())}</span>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan }</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-target" 
                                        placeholder="Masukkan Nilai"
                                        value="${ data.targetBalaiDefualtValue }"
                                        data-row-id="${ data.id }"
                                    onkeyup="return this.value = formatRupiah(this.value, '')"
                                    data-pktype="balai">
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan }</span>
                                    </div>
                                </div>
                            </td>
                        `
                    } else {

                        renderInputTarget = `
                        <td>
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    class="form-control __inputTemplateRow-target" 
                                    placeholder="Masukkan Nilai"
                                    value="${ data.outcomeSatkerValue }"
                                    data-row-id="${ data.id }"
                                    data-targetSatuan = "${ data.target_satuan}"
                                    ${(data.template_id == '15' && data.id == "151010") ||
                                    (data.template_id == '14' && data.id == "141009") ? "maxlength = 1":""}
                                    onkeyup="${(data.template_id == '15' && data.id == "151010") 
                                    ||
                                    (data.template_id == '14' && data.id == "141009") ? "return this.value = this.value.replace(/[^A-Da-d]/g, '')":"return this.value = formatRupiah(this.value, '')"}" data-pktype="satker"
                                    ${data.template_id === '5' || data.template_id === '6'|| data.template_id === '9'  || data.template_id === '11' || data.template_id === '12' || data.template_id === '13'  || data.template_id === '14' || data.template_id === '15'  || data.template_id === '16'|| data.template_id === '17' || data.template_id === '18' || data.template_id === '19'|| data.template_id === '20' || data.template_id === '29' || _templateType === 'eselon2' ||  _tahun === '2023' ? '' :'readonly' }>
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan.split(';')[0]}</span>
                                    </div>
                            </div>
                        </td>
                        `
                    }


                    //             <div class="input-group-append">
                    //                              <select class="form-control select-target-satuan" data-row-id="${data.id}">
                    //         ${data.target_satuan.split(';').map(function(satuan) {
                    //         return `<option value="${satuan.trim()}">${satuan.trim()}</option>`;
                    //     }).join('')}

                    // </select>
                    //                         </div>
                    // if (paramsBtnPaket == "lihat" || paramsBtnPaket == "edit") {

                    //     $.ajax({
                    //         url: "<?php echo site_url('dokumenpk/detail/') ?>" + DocID,
                    //         type: 'GET',
                    //         success: (res) => {
                    //             if (res.paket) {

                    //                 const idPaketArray = res.paket
                    //                     .filter(item => item.template_row_id == data.id)
                    //                     .map(item => {
                    //                             return {
                    //                                 paketId: item.idpaket,
                    //                                 target_nilai: item.target_value, // Isi dengan nilai target_nilai yang sesuai
                    //                                 target_satuan: item.target_unit, // Isi dengan nilai target_satuan yang sesuai
                    //                                 outcome_nilai: item.output_value, // Isi dengan nilai outcome_nilai yang sesuai
                    //                                 outcome_satuan: item.output_unit // Isi dengan nilai outcome_satuan yang sesuai
                    //                             };
                    //                         }

                    //                     );

                    //                 selectedItems = idPaketArray;

                    //                 sessionStorage.setItem(data.id, JSON.stringify(idPaketArray));

                    //                 var totalPaketElement = $('[data-rowid="' + data.id + '"]').find('.totalpaket');
                    //                 var elem = $('[data-rowid="' + data.id + '"]');

                    //                 elem.attr('data-satkerid', res.dokumen.satkerid ?? '0');
                    //                 totalPaketElement.html(selectedItems.length);




                    //             }

                    //         }
                    //     });


                    // }

                    if (paramsBtnPaket == "uptBalai-add") {

                        const idPaketArray = data.paket
                            .filter(item => data.listSatker.includes(item.satkerid))
                            .map(item => {
                                    return {
                                        paketId: item.idpaket,
                                        target_nilai: item.target_value,
                                        target_satuan: item.target_unit,
                                        outcome_nilai: item.output_value,
                                        outcome_satuan: item.output_unit
                                    };
                                }

                            );

                        selectedItems = idPaketArray;
                        sessionStorage.setItem(data.id, JSON.stringify(idPaketArray));
                    }

                    rows += `
                        <tr>
                            <td class="text-center align-middle" width="50px">
                                <input type="checkbox" name="form-check-row" checked />
                            </td>
                            <td class="align-middle" width="50px">${ rowNumber++ }</td>
                            <td class="align-middle">${ data.title } 

                                            ${data.template_id === '5' || data.template_id === '6' || data.template_id === '9' ||data.template_id === '11' || data.template_id === '12' || data.template_id === '13'  || data.template_id === '14' || data.template_id === '15'  || data.template_id === '16'|| data.template_id === '17' || data.template_id === '18' || data.template_id === '19'|| data.template_id === '20' || data.template_id === '29' || _templateType === 'eselon2' ||  _tahun === '2023' ? '' : `
                <button class="font-weight-bold btn-light-success btn-sm mr-2 paket" 
                        title="pilih paket" 
                        data-dokid="${DocID || 0}" 
                        data-templateid="${data.template_id}" 
                        data-indikator="${data.title}" 
                        data-rowid="${data.id}" 
                        data-outputsatuan="${data.target_satuan}" 
                        data-outcomesatuan="${data.outcome_satuan}" 
                     

                       data-satkerid="${_satkerId}">
                    Paket 
                    <span class="label label-sm label-white ml-2 totalpaket">
                    ${paramsBtnPaket === "uptBalai-add" ? selectedItems.length : data.paket.length}
                    </span>
                </button>
                `}


                           
                            

                            
                            </td>
                            ${renderInputTarget}
                            
                            <td class="${classDNoneOutcome}">
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-outcome" 
                                        placeholder="Masukkan Nilai"
                                        value="${ data.outcomeDefaultValue }"
                                        data-row-id="${ data.id }"
                                       ${(data.template_id == '15' && data.id == "151010") ||
                                       (data.template_id == '14' && data.id == "141009") ? "maxlength = 1":""}
                                    onkeyup="${ (data.template_id == '15' && data.id == "151010") ||
                                    (data.template_id == '14' && data.id == "141009") ? "return this.value = this.value.replace(/[^A-Da-d]/g, '')":"return this.value = formatRupiah(this.value, '')"}"
                                        ${data.template_id === '5' || data.template_id === '6'  || data.template_id === '9' ||data.template_id === '11' || data.template_id === '12' || data.template_id === '13'  || data.template_id === '14' || data.template_id === '15'  || data.template_id === '16'|| data.template_id === '17' || data.template_id === '18' || data.template_id === '19'|| data.template_id === '20' || data.template_id === '29' || _templateType === 'eselon2' ||  _tahun === '2023' ? '' :"readonly"}
                                        >
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.outcome_satuan }</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `
                    break;



            }


        });




        return rows
    }



    function renderFormTemplate_rowKegiatan(_data) {
        let list = ''

        _data.forEach((data, key) => {
            list += renderFormTemplate_rowKegiatan_item({
                id: data.id,
                nama: data.nama,
                rowType: 'text'
            })
            // list += `
            //     <tr
            //         data-kegiatan-id="${data.id}"
            //         data-kegiatan-nama="${data.nama}"
            //     >
            //         <td class="align-middle">
            //             ${data.nama}
            //         </td>
            //         <td class="align-middle">
            //             <div class="input-group d-none">
            //                 <div class="input-group-prepend">
            //                     <span class="input-group-text">Rp. </span>
            //                 </div>
            //                 <input 
            //                     class="form-control" 
            //                     name="kegiatan-anggaran" 
            //                     value="0" 
            //                     placeholder="Nominal Anggaran"
            //                     style = "text-align: right;" 
            //                     onkeyup="return this.value = formatRupiah(this.value, '')"
            //                 >
            //             </div>
            //         </td>
            //         <td class="align-middle">
            //             <button class="btn btn-sm btn-danger">
            //                 <i class="fas fa-trash"></i>
            //             </button>
            //         </td>
            //     </tr>
            // `
        });

        return list
    }



    function renderFormTemplate_rowKegiatan_item(params = {
        id: '',
        nama: '',
        anggaran: 0,
        rowType: '' // input || text
    }) {
        let renderKegiatanNama,
            kegiatananggaran = params.hasOwnProperty('anggaran') ? params.anggaran : '0'

        switch (params.rowType) {
            case 'input':
                let inputKegiatanManualDefaultValue = params.nama != '-' ? params.nama : ''

                if (inputKegiatanManualDefaultValue == '') {
                    renderKegiatanNama = `
                        <select class="select2 w-100 __nama-kegiatan-manual"></select>
                    `
                } else {
                    params.id = '?'
                    renderKegiatanNama = inputKegiatanManualDefaultValue
                }

                break;

            case 'text':
                renderKegiatanNama = params.nama
                break;

            default:
                renderKegiatanNama = ''
                break;
        }

        return `
            <tr
                data-kegiatan-id="${params.id}"
                data-kegiatan-nama="${params.nama}"
            >
                <td colspan="1" class="align-middle">
                    <div>
                        ${renderKegiatanNama}
                    </div>
                </td>

                <td class="align-middle">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp. </span>
                        </div>
                        <input 
                            class="form-control" 
                            name="kegiatan-anggaran" 
                            value="${kegiatananggaran}" 
                            placeholder="Nominal Anggaran"
                            style = "text-align: right;" 
                            onkeyup="return this.value = formatRupiah(this.value, '')"
                        >
                    </div>
                </td>
                <td class="align-middle">
                    <button class="btn btn-sm btn-danger __remove-item-kegiatan">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `
    }



    function renderFormTemplate_listInfo(_data) {
        let list = ''
        _data.forEach((data, key) => {
            list += `
                <li class="list-group-item border-0 py-1 px-4">
                    ${ data.info }
                </li>
            `
        });

        return list
    }



    function renderFormTemplate_ttdPihak2(_dataPenandatanganPihak2, _inputDefaultValue) {
        let prefixJabatanPenandatangan = '', //_dataPenandatanganPihak2.includes('KEPALA') ? '' : 'KEPALA',
            renderJalabatan = `<div><small class="title-ttd-pihak2">${prefixJabatanPenandatangan + ' ' + _dataPenandatanganPihak2}</small></div>`

        if (_dataPenandatanganPihak2 == '') {
            let smallTitleClass = _inputDefaultValue == '' ? 'class="title-ttd-pihak2"' : ''
            renderJalabatan = `
                <div><small ${smallTitleClass}>${_inputDefaultValue}</small></div>
                <input class="form-control d-none" name="ttd-pihak2-jabatan" placeholder="Jabatan Penanda Tangan"  value="${ _inputDefaultValue }" onkeypress="return inputHarusHuruf(event)" />
            `
        }

        return `
            ${renderJalabatan}
            <input class="form-control" name="ttd-pihak2" placeholder="Masukkan Nama Penanda Tangan" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return inputHarusHurufDenganTitik(event)" />
        `
    }



    function renderFormTemplate_opsiKota(_dataKota) {
        let renderOptions = ''

        _dataKota.forEach((data, key) => {
            renderOptions += `<option value='${data.kdlokasi + '-' + data.kdkabkota}'>${data.nmkabkota}</option>`
        });

        return renderOptions
    }



    function renderFormTemplate_opsiBulan(_data) {
        let renderOptions = `
            <option value=''>
                Pilih Bulan
            </option>
        `

        _data.forEach((data, key) => {
            // let isSelected = key == date.getMonth() ? 'selected' : ''
            // monthToHide = key > date.getMonth() ? '_option-month-to-hide d-none' : ''
            let isSelected = '',
                monthToHide = ''


            renderOptions += `
                <option class="${monthToHide}" value='${key+1}' ${isSelected}>
                    ${data}
                </option>
            `
        });

        return renderOptions
    }

    $(document).on('change', '.opsi-bulan', function(e) {
        var bulan = $(this).val();
        var tahun = $("select[name=created-tahun]").val();
        var tanggalDropdown = $("select[name=created-day");

        // Membuat objek Date dengan bulan dan tahun yang dipilih
        var tanggalMaksimum = new Date(tahun, bulan, 0);

        // Mendapatkan tanggal maksimum untuk bulan dan tahun tersebut
        var tanggalMaksimumFormatted = tanggalMaksimum.getDate();

        // Mengosongkan pilihan tanggal sebelum menambahkan yang baru
        tanggalDropdown.empty();
        tanggalDropdown.append(`<option value="">Pilih Tanggal</option>`);
        for (let iTanggal = 1; iTanggal <= tanggalMaksimumFormatted; iTanggal++) {
            tanggalDropdown.append(`<option value="${iTanggal}">${iTanggal}</option>`);
        }
    });



    function renderFormTemplate_opsiTahun(_data) {
        let renderOptions = ''

        for (let iTahun = (parseInt(_data)); iTahun <= (parseInt(_data)); iTahun++) {

            let isSelected = (iTahun == parseInt(_data) ? 'selected' : '')

            renderOptions += `<option ${isSelected}>${iTahun}</option>`
        }
        // for (let iTahun = (parseInt(_data)); iTahun <= (parseInt(_data)); iTahun++) {

        //     let isSelected = iTahun == date.getFullYear() ? 'selected' : ''

        //     renderOptions += `<option ${isSelected}>${iTahun}</option>`
        // }

        // for (let iTahun = _data; iTahun <= (parseInt(_data)+3); iTahun++) {
        //     let selected = iTahun == _data ? 'selected=selected' : ''
        //     renderOptions += `<option ${selected}>${iTahun}</option>`
        // }

        return renderOptions
    }



    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
</script>
<!-- modal render pilih paket -->

<script>
    $(document).on('click', '.paket', function() {
        var selectedItems = [];
        $('#modalPilihPaket').modal('show');
        $('.modal.btn-modal-full').trigger('click');

        let satkerId = $(this).data('satkerid');
        let templateId = $(this).data('templateid');

        var balaiCreateSatker = $(".__opsi-template").attr("data-balai-create-satker");



        if (balaiCreateSatker != undefined) {

            satkerId = balaiCreateSatker;

        }



        let indikator = $(this).data('indikator');
        let docId = $(this).data('dokid');
        let indikatorID = $(this).data('rowid');
        // let output_satuan = $('.select-target-satuan[data-row-id=' + indikatorID + ']').val();
        let output_satuan = $(this).data('outputsatuan');
        let outcome_satuan = $(this).data('outcomesatuan');


        $('#modalFormTitlePaket').html(``);
        $('#modalFormTitlePaket').html(`<h6>Pilih Paket</h6>
                        <small>Indikator : <b>${ indikator }</b></small>
                       
                        `);


        $('.save-btn-paket').removeAttr("data-indikatorid");
        $('.save-btn-paket').attr("data-indikatorid", indikatorID)


        var storedItems = sessionStorage.getItem(indikatorID);

        if (storedItems) {
            selectedItems = JSON.parse(storedItems);
        }


        //get paket
        $.ajax({
            url: "<?php echo site_url('api/getpaket') ?>",
            type: 'GET',
            data: {
                satkerId: satkerId,
                templateId: templateId
            },
            success: (res) => {

                if (res.message != 'tidak ada data') {
                    const tbody = $('#tbody');
                    tbody.empty();
                    var jsonData = JSON.parse(res);

                    jsonData.forEach(function(balai, index) {
                        if (index === 0) {
                            tbody.append(`
                    <tr style="background-color:#89CFF0" class="sticky-header-2">
                    <td>-</td>
                    <td colspan = "10"><strong>${balai.balai}</strong></td>
                    </tr>`);
                        }
                        tbody.append(`
                    
                    <tr style="background-color:#b6dced" class="sticky-header-3">
                    <td><strong>${balai.satkerid}</strong></td>
                    <td colspan = "10"><strong>${balai.satker}</strong></td>
                    </tr>
                

                   `);

                        balai.paket.forEach(function(paket, index) {

                            trClass = '';
                            output_from_satrker = ''
                            var checkboxHtml = `<input type="checkbox" val="${paket.paketId}" class="checkbox"`;
                            if (paramsBtnPaket == "lihat" || paramsBtnPaket == 'uptBalai-add') {
                                checkboxHtml += 'disabled';
                                $(".checkbox-click").attr("disabled", true)
                                $('.save-btn-paket').addClass('d-none');
                            } else {
                                $('.save-btn-paket').removeClass('d-none');
                            }
                            // if (selectedItems.includes(paket.paketId)) {
                            if (selectedItems.some(item => item.paketId === paket.paketId)) {
                                checkboxHtml += ' checked'; // Set checked attribute
                                trClass = "style='background-color:#e0f2e9'"
                                // Cari objek yang sesuai dengan paket.paketId
                                const selectedPaket = selectedItems.find(item => item.paketId === paket.paketId);

                                // Isi output_from_satrker dengan nilai target_satuan
                                if (selectedPaket) {
                                    output_from_satrker = selectedPaket.target_satuan;
                                } else {
                                    output_from_satrker = ''; // Atur ke nilai default jika tidak ditemukan
                                }
                            }

                            checkboxHtml += '>';

                            tbody.append(`
                          <tr ${trClass}>
                          <td>${checkboxHtml}</td>
                            <td>${paket.paketId}</td>
                            <td>${paket.label}</td>
                            <td>${paket.vol}</td>
                            <td>${paket.satuan}</td>
                            <td>${paket.paguDipa}</td>
                            <td>${paket.realisasi}</td>
                            <td>${paket.persenKeu}</td>
                            <td>${paket.persenFis}</td>
                            <td>
                            <div class="form-group form-group-last row">
								<div class="form-group-sub">
									<label class="form-control-label">Vol Output :</label>
									<input type="text" class="form-control target_nilai checkbox-click" name="target_nilai" placeholder="" onkeyup="return this.value = formatRupiah(this.value, '')" ${selectedItems.some(item => item.paketId === paket.paketId)? "value=" +selectedItems.find(item => item.paketId === paket.paketId).target_nilai:"disabled"}>
								</div>
                                <div class="form-group-sub">
									<label class="form-control-label">Satuan Output :</label>
                                                <div class="input-group-append">
                                                <select class="form-control checkbox-click" ${selectedItems.some(item => item.paketId === paket.paketId)? "value=" +selectedItems.find(item => item.paketId === paket.paketId).target_nilai:"disabled"} name="target_satuan">
    ${output_satuan.split(';').map(function(satuan) {
        const isSelected = selectedItems.some(item => item.paketId === paket.paketId && item.target_satuan === satuan.trim());
        return `<option value="${satuan.trim()}" ${isSelected ? 'selected' : ''}>${satuan.trim()}</option>`;
    }).join('')}
</select>
                                            </div>



								</div>
							</div>
                            
                            </td>
                            <td>
                            <div class="form-group form-group-last row">
								<div class="form-group-sub">
									<label class="form-control-label center">Vol Outcome :</label>
									<input type="text" class="form-control outcome_nilai checkbox-click" name="outcome_nilai" placeholder="" onkeyup="return this.value = formatRupiah(this.value, '')" ${selectedItems.some(item => item.paketId === paket.paketId)? "value=" +selectedItems.find(item => item.paketId === paket.paketId).outcome_nilai:"disabled"}>
								</div>
                                <div class="form-group-sub">
									<label class="form-control-label">Satuan Outcome :</label>
								
									<input type="text" class="form-control outcome_satuan" name="outcome_satuan" value="${selectedItems.some(item => item.paketId === paket.paketId) ? selectedItems.find(item => item.paketId === paket.paketId).outcome_satuan : outcome_satuan} " disabled>
								</div>
							</div>
                            
                            </td>
                          </tr>
                        `);

                            // Mendengarkan perubahan status kotak centang
                            $('input.checkbox').on('change', function() {
                                if (this.checked) {
                                    // Kotak centang dicentang, maka menonaktifkan input dengan class "target_nilai"
                                    $(this).closest('tr').find('.checkbox-click').prop('disabled', false);
                                    $(this).closest('tr').attr('style', 'background-color:#e0f2e9');

                                } else {
                                    // Kotak centang tidak dicentang, maka mengaktifkan kembali input dengan class "target_nilai"
                                    $(this).closest('tr').find('.checkbox-click').prop('disabled', true);
                                    $(this).closest('tr').removeAttr('style');

                                }
                            });
                        });


                    })
                }
            }
        })





    });
</script>

<script>
    $(document).on('click', '.save-btn-paket', function(e) {
        e.preventDefault();
        indikatorId = $(this).attr("data-indikatorid");
        var selectedItems = [];
        var errorMessages = [];
        var totalJumlahTarget = 0;
        var totalJumlahOutcome = 0;
        var TargetlengthFix = 2;
        var OutcomelengthFix = 2;

        $('.checkbox:checked').each(function() {
            var paketId = $(this).attr('val');
            var target_nilai = $(this).closest('tr').find('input[name=target_nilai]').val();
            var target_satuan = $(this).closest('tr').find('select[name=target_satuan]').val();
            var outcome_nilai = $(this).closest('tr').find('input[name=outcome_nilai]').val();
            var outcome_satuan = $(this).closest('tr').find('input[name=outcome_satuan]').val();

            if (target_nilai.trim() === '') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Target Nilai yang belum diisi.');
            } else if (target_satuan.trim() === '') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Target Satuan yang belum diisi.');


            } else if (outcome_nilai.trim() === '') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Outcome Nilai yang belum diisi.');


            } else if (outcome_satuan.trim() === '') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Outcome Satuan yang belum diisi.');


            } else {
                selectedItems.push({
                    paketId: paketId,
                    target_nilai: target_nilai,
                    target_satuan: target_satuan,
                    outcome_nilai: outcome_nilai,
                    outcome_satuan: outcome_satuan
                });



                target_nilai_number_remove_titik = target_nilai.replace('.', '');
                outcome_nilai_number_remove_titik = outcome_nilai.replace('.', '');

                target_nilai_number = parseFloat(target_nilai_number_remove_titik.replace(',', '.'));
                outcome_nilai_number = parseFloat(outcome_nilai_number_remove_titik.replace(',', '.'));


                let elm_output_satuan_indikator = $('.__inputTemplateRow-target[data-row-id=' + indikatorId + ']').data('targetsatuan');
                let output_satuan_indikator = elm_output_satuan_indikator.split(";")[0];

                if (target_satuan == output_satuan_indikator) {
                    totalJumlahTarget += target_nilai_number;
                }


                if (target_satuan.trim().toLowerCase() === "m3/detik") {

                    TargetlengthFix = 4;
                }

                if (target_satuan.trim().toLowerCase() === "juta m3") {

                    TargetlengthFix = 6;
                }



                if (outcome_satuan.trim().toLowerCase() === "m3/detik") {

                    OutcomelengthFix = 4;
                }

                if (outcome_satuan.trim().toLowerCase() === "juta m3") {

                    OutcomelengthFix = 6;
                }
                totalJumlahOutcome += outcome_nilai_number;

            }

        });
        if (errorMessages.length > 0) {
            // Menampilkan pesan kesalahan jika ada
            errorMessages.forEach(function(message) {
                Swal.fire('Peringatan', message, 'warning');
            });
        } else if (selectedItems.length > 0) {
            sessionStorage.setItem(indikatorId, JSON.stringify(selectedItems));
            var totalPaketElement = $('[data-rowid="' + indikatorId + '"]').find('.totalpaket');
            totalPaketElement.html(selectedItems.length);



            var jumlahDesimal_target = (totalJumlahTarget % 1 === 0) ? 0 : totalJumlahTarget.toFixed(TargetlengthFix).toString().split('.')[1].length;
            var jumlahDesimal_outcome = (totalJumlahOutcome % 1 === 0) ? 0 : totalJumlahOutcome.toFixed(OutcomelengthFix).toString().split('.')[1].length;



            totalJumlahTargetDenganKoma = totalJumlahTarget.toLocaleString('id-ID', {
                // minimumFractionDigits: jumlahDesimal_target
                minimumFractionDigits: jumlahDesimal_target
            });

            totalJumlahOutcomeDenganKoma = totalJumlahOutcome.toLocaleString('id-ID', {
                minimumFractionDigits: jumlahDesimal_outcome
            });

            var totalTarget_nilai = $('.__inputTemplateRow-target[data-row-id=' + indikatorId + ']')
            var totalOutcome_nilai = $('.__inputTemplateRow-outcome[data-row-id=' + indikatorId + ']')
            totalTarget_nilai.val(totalJumlahTargetDenganKoma);
            totalOutcome_nilai.val(totalJumlahOutcomeDenganKoma);

            $('#modalPilihPaket').modal('hide');
        } else {
            Swal.fire(
                'Gagal Menyimpan',
                'Tidak ada paket yang dipilih !',
                'warning'
            )
        }




    });
</script>