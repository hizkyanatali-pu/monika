<?php echo script_tag('js/imask.js'); ?>

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

    $(document).on('change', 'select[name=filter-satker]', function() {
        window.location.href = "<?php echo base_url('renstra-balai-satker') ?>/" + $(this).val()
    })

    $(document).on('click', '.__opsi-tahun-renstra', function() {
        $('#modalOpsiTahun').modal('show');
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
        paramsBtnPaket = "buat-dokumen";

        $.ajax({
            url: "<?php echo site_url('renstra/get-template/') ?>" + dataID,
            type: 'GET',
            data: {},
            success: (res) => {
                preapreForm_afterChooseTemplate({
                    templateId: dataID,
                    templateTitle: $(this).text(),
                    data: res,
                    target: 'create',
                    tahunForEdit: $("#tahunAnggaran").val()
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
        let RowId = $(this).first().data('id');
        // let parentRowId = $('.ogiat-row').data('parent-rowid');




        if (!$(this).is(':checked')) {

            if (typeof rowid === 'undefined') {
                element_parentsColumn.addClass('disabled')
                element_parentsColumn.find('input').attr('readonly', 'readonly')
                element_parentsColumn.find('input').val('')
            } else {
                element_parentsColumn.addClass('disabled')
                element_parentsColumn.find('input').attpr('readonly', 'readonly')
                element_parentsColumn.find('select').attr('disabled', 'disabled')
                element_parentsColumn.find('button.paket').attr('disabled', 'true')
                element_parentsColumn.find('input').val('')
                element_parentsColumn.find('.totalpaket').html("0")
            }



            // if (parentRowId == RowId) {
            $(`.ogiat-row[data-parent-rowid="${RowId}"]`).each(function() {
                $(this).find('td').addClass('disabled');
                $(this).find('td').find('input').attr('readonly', 'readonly');
                $(this).find('td').find('select').attr('disabled', 'disabled');
                $(this).find('td').find('button.paket').attr('disabled', 'disabled');
            });
            // }


            sessionStorage.removeItem(rowid);

            // sessionStorage.clear()
        } else {
            if (typeof rowid === 'undefined') {
                element_parentsColumn.removeClass('disabled')
                element_parentsColumn.find('input').removeAttr('readonly')


                $(`.ogiat-row[data-parent-rowid="${RowId}"]`).each(function() {
                    $(this).find('td').removeClass('disabled');
                    $(this).find('td').find('input').removeAttr('readonly');
                    $(this).find('td').find('select').removeAttr('disabled');
                    $(this).find('td').find('button.paket').removeAttr('disabled');
                });
            } else {
                element_parentsColumn.removeClass('disabled')

                if (element_parentsColumn.find('input[data-pktype]').attr('data-pktype') == "balai") {
                    element_parentsColumn.find('input').removeAttr('readonly')
                }
                element_parentsColumn.find('select').removeAttr('disabled')
                element_parentsColumn.find('button.paket').removeAttr('disabled')


                $(`.ogiat-row[data-parent-rowid="${RowId}"]`).each(function() {
                    $(this).find('td').removeClass('disabled');
                    $(this).find('td').find('input').removeAttr('readonly');
                    $(this).find('td').find('select').removeAttr('disabled');
                    $(this).find('td').find('button.paket').removeAttr('disabled');
                });


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
            let savingMessageElement = $('<center>menyimpan data</center>');
            element_btnSaveDokumen.parent().append(savingMessageElement);


            // $('input[name=total-anggaran]').prop("disabled", false)

            let formData = getFormValue();
            $.ajax({
                url: "<?php echo site_url('renstra/create') ?>",
                type: 'POST',
                data: formData,
                success: (res) => {
                    if (res.status) {
                        location.reload()
                    } else {
                        Swal.fire('Gagal', res.message, 'error').then(result => {
                            location.reload()
                        })
                    }
                },
                fail: (xhr) => {
                    alert('Terjadi kesalahan pada sistem')
                    console.log(xhr)
                }
            })

            return;



            // if ($(this).attr('data-dokumen-id')) {
            //     formData['revision_dokumen_id'] = $(this).data('dokumen-id')
            //     formData['revision_dokumen_master_id'] = $(this).data('dokumen-master-id')

            //     Swal.fire({
            //         title: "Anda yakin akan mengedit dokumen ini ?",
            //         html: `<textarea class="form-control" name="pesan-koreksi-dokumen" rows="10" placeholder="Tulis pesan"></textarea>`,
            //         confirmButtonText: "Kirim",
            //         cancelButtonText: "Batal",
            //         showLoaderOnConfirm: true,
            //         showCancelButton: true,
            //         onCancel: () => {
            //             element_btnSaveDokumen.removeClass('d-none');

            //         },
            //         preConfirm: () => {
            //             const pesanRevisi = $('textarea[name=pesan-koreksi-dokumen]').val();

            //             if (!pesanRevisi) {
            //                 Swal.showValidationMessage('Pesan harus diisi');
            //                 return false;
            //             }

            //             formData['revision_message'] = $('textarea[name=pesan-koreksi-dokumen]').val();
            //             $.ajax({
            //                 url: "<?php echo site_url('dokumenpk/create') ?>",
            //                 type: 'POST',
            //                 data: formData,
            //                 success: (res) => {
            //                     if (res.status) {
            //                         location.reload()
            //                     } else {
            //                         Swal.fire(
            //                             'Gagal',
            //                             res.message,
            //                             'error'
            //                         ).then(result => {
            //                             location.reload()
            //                         })
            //                     }
            //                 },
            //                 fail: (xhr) => {
            //                     alert('Terjadi kesalahan pada sistem')
            //                     console.log(xhr)
            //                 }
            //             })
            //         }
            //     })
            // } else {
            // $.ajax({
            //     url: "<?php echo site_url('renstra/create') ?>",
            //     type: 'POST',
            //     data: formData,
            //     success: (res) => {
            //         if (res.status) {
            //             location.reload()
            //         } else {
            //             Swal.fire(
            //                 'Gagal',
            //                 res.message,
            //                 'error'
            //             ).then(result => {
            //                 location.reload()
            //             })
            //         }
            //     },
            //     fail: (xhr) => {
            //         alert('Terjadi kesalahan pada sistem')
            //         console.log(xhr)
            //     }
            // })
            // }

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
                    url: "<?php echo site_url('renstra/change-status') ?>",
                    type: "POST",
                    data: {
                        csrf_test_name: $('input[name=csrf_test_name]').val(),
                        dokumenType: 'hold-edit',
                        dataID: dataID,
                        message: $('textarea[name=pesan-revisi-dokumen]').val(),
                        newStatus: 'hold'
                    },
                    success: (res) => {
                        if (saveDokumenValidation()) {

                            let oldButtonText = element_btnSaveEditDokumen.text()
                            element_btnSaveEditDokumen.addClass('d-none')
                            element_btnSaveEditDokumen.parent().append('<center>menyimpan dokumen</center>')

                            let formData = getFormValue(),
                                dataId = $(this).data('id')

                            formData['id'] = $(this).data('id')
                            formData['csrf_test_name'] = res.token
                            $.ajax({
                                url: "<?php echo site_url('renstra/editDokumen') ?>",
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
                    }
                })
            }
        })
    })
    $(document).on('click', '.__prepare-revisi-dokumen', function() {
        paramsBtnPaket = "edit";

        let document_id = $(this).data('id')
        element_btnSaveEditDokumen.data('id', document_id)
        tahunForEdit = $(this).data('tahun');

        prepareRevisiDocument({
            dataId: $(this).data('id'),
            templateId: $(this).data('template-id'),
            tahunForEdit: tahunForEdit,
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
        paramsBtnPaket = $(this).data('type') == "uptBalai-add" ? "uptBalai-add" : "lihat"
        tahunForEdit = $(this).data('tahun');


        prepareRevisiDocument({
            dataId: $(this).data('id'),
            templateId: $(this).data('template-id'),
            tahunForEdit: tahunForEdit,
            beforeModalMount: () => {
                // $('#modalForm').find('.container-revision-alert').addClass('d-none')
                $('#modalForm').find('input').attr('disabled', 'disabled')
                $('#modalForm').find('select').attr('disabled', 'disabled')
                // $('#modalForm').find('.modal-footer').addClass('d-none')

                $('#modalForm').find('.__remove-item-kegiatan').addClass('d-none')
                $('#modalForm').find('#__add-item-kegiatan').addClass('d-none')
                $('.__save-dokumen').addClass('d-none')


                if ($(this).data('type') == "Admin" || $(this).data('type') == "satker" || $(this).data('type') == "balai") {
                    $('.__save-update-dokumen').addClass('d-none')
                }
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
        tahunForEdit = $(this).data('tahun');
        prepareRevisiDocument({
            dataId: documentId,
            templateId: $(this).data('template-id'),
            tahunForEdit: tahunForEdit,
            beforeModalMount: () => {
                element_btnSaveEditDokumen.data('id', documentId);
                $('.__save-dokumen').addClass('d-none')
                $('.__save-update-dokumen').removeClass('d-none')
                if ($(this).data('type') == "Admin" || $(this).data('type') == "satker" || $(this).data('type') == "balai") {
                    $('.__save-update-dokumen').addClass('d-none')
                }
                // console.log($('.__save-update-dokumen'))
                $('#modalForm').find('.container-revision-alert').addClass('d-none')
            }
        })
    })

    function prepareRevisiDocument(params = {
        dataId: '',
        templateId: '',
        tahunForEdit: '',
        beforeModalMount: () => {}
    }) {
        const promiseGetTemplate = new Promise((resolve, reject) => {
            var templateId = params.templateId,
                dataId = params.dataId,
                tahunForEdit = params.tahunForEdit




            $.ajax({
                url: "<?php echo site_url('renstra/get-template/') ?>" + templateId + "/" + dataId,
                type: 'GET',
                success: (res) => {
                    preapreForm_afterChooseTemplate({
                        dataId: dataId,
                        templateId: templateId,
                        data: res,
                        target: 'koreksi',
                        tahunForEdit: tahunForEdit
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
                url: "<?php echo site_url('renstra/detail/') ?>" + res.dataId,
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

                        elementInput_target.val(formatRupiah(data.target_value.toString().replaceAll('.', ',')))
                        data.target_sat ? elementInput_target_satuan.val(data.target_sat) : ''
                        elementInput_outcome.val(formatRupiah(data.outcome1_value.toString().replaceAll('.', ',')))

                        const foundRow = getRows.find(row => row.id === data.template_row_id);
                        if (foundRow) {
                            // Menggunakan nilai 'satkerid' dari 'getRows' jika ditemukan
                            satkerIdDefault = foundRow.satkerid;
                        }
                        var elem = $('[data-rowid="' + data.template_row_id + '"]');
                        elem.attr('data-satkerid', res.dokumen.satkerid || satkerIdDefault);
                        if (data.is_checked == '0') elementInput_target.parents('tr').find('input:checkbox[name=form-check-row]').trigger('click')

                    })
                    unique = res.paket.map(e => e['template_ogiat_id']).map((e, i, final) => final.indexOf(e) === i && i)
                        .filter(obj => res.paket[obj])
                        .map(e => res.paket[e]["template_ogiat_id"]);
                    let duplicateIds = res.paket.map(e => e['template_ogiat_id']).map((e, i, final) => final.indexOf(e) !== i && i)
                        .filter(obj => res.paket[obj])
                        .map(e => res.paket[e]["template_ogiat_id"])

                    if (duplicateIds.length > 0) {
                        duplicateIds.forEach(id => {
                            let duplicate = []
                            res.paket.forEach(item => {

                                if (item.template_ogiat_id === id) {
                                    duplicate.push({
                                        oGiatId: item.template_ogiat_id,
                                        paketId: item.idpaket,
                                        target_nilai: item.output_val,
                                        target_satuan: item.output_sat,
                                        outcome1_nilai: item.outcome1_val,
                                        outcome1_satuan: item.outcome1_sat.replace(/\//g, ''),
                                        outcome2_nilai: item.outcome2_val ?? "",
                                        outcome2_satuan: item.outcome2_sat.replace(/\//g, '') ?? "",
                                        outcome3_nilai: item.outcome3_val ?? "",
                                        outcome3_satuan: item.outcome3_sat.replace(/\//g, '') ?? "",
                                    })
                                }
                            })

                            sessionStorage.setItem('Paket_' + id, JSON.stringify(duplicate));
                        })


                    }

                    // else { // yusfi komen untuk edit
                    unique.forEach(id => {
                        let duplicate = []
                        res.paket.forEach(item => {

                            if (item.template_ogiat_id === id) {
                                duplicate.push({
                                    oGiatId: item.template_ogiat_id,
                                    paketId: item.idpaket,
                                    target_nilai: item.output_val,
                                    target_satuan: item.output_sat,
                                    outcome1_nilai: item.outcome1_val,
                                    outcome1_satuan: item.outcome1_sat.replace(/\//g, ''),
                                    outcome2_nilai: item.outcome2_val ?? "",
                                    outcome2_satuan: item.outcome2_sat.replace(/\//g, '') ?? "",
                                    outcome3_nilai: item.outcome3_val ?? "",
                                    outcome3_satuan: item.outcome3_sat.replace(/\//g, '') ?? "",
                                })
                            }
                        })
                        sessionStorage.setItem('Paket_' + id, JSON.stringify(duplicate));
                    })

                    // }
                    res.ogiat.forEach((data, key) => {
                        let cleanedOutput = data.output_sat;
                        let cleanedOutcome = data.outcome1_sat;
                        let cleanedOutcome2 = data.outcome2_sat;
                        let cleanedOutcome3 = data.outcome3_sat;
                        if (cleanedOutput == '%') {
                            cleanedOutput = 'percent'

                        }
                        if (cleanedOutcome == '%') {
                            cleanedOutcome = 'percent'

                        }
                        if (cleanedOutcome2 == '%') {
                            cleanedOutcome2 = 'percent'

                        }
                        if (cleanedOutcome3 == '%') {
                            cleanedOutcome3 = 'percent'

                        }



                        let parent = $('tr[data-parent-rowid=' + data.template_ogiat_id + ']')
                        parent.find(`input[type="checkbox"][data-parent-rowid=${data.template_ogiat_id}]`).prop("checked", true);
                        parent.find('td').removeClass('disabled')
                        parent.find(`.paket`).removeAttr('disabled')
                        parent.find(`.__targetValue-${cleanedOutput}[data-row-id=${data.template_ogiat_id}]`).val(data.output_val)
                        parent.find(`.__outcome1Value-${cleanedOutcome ? cleanedOutcome.replace(/\//g, ''):''}[data-row-id=${data.template_ogiat_id}]`).val(data.outcome1_val)
                        parent.find(`.__outcome2Value-${cleanedOutcome2 ? cleanedOutcome2.replace(/\//g, ''):'' }[data-row-id=${data.template_ogiat_id}]`).val(data.outcome2_val)
                        parent.find(`.__outcome3Value-${cleanedOutcome3 ? cleanedOutcome3.replace(/\//g, ''):''}[data-row-id=${data.template_ogiat_id}]`).val(data.outcome3_val)

                        const outputKegiatanItems = {
                            oGiatId: [{
                                oGiatId: data.template_ogiat_id
                            }],
                            target: [{
                                targetSatuan: data.output_sat,
                                targetNilai: data.output_val
                            }],
                            outcome1: [{
                                outcome1Satuan: data.outcome1_sat,
                                outcome1Nilai: data.outcome1_val,
                            }],
                            outcome2: [{
                                outcome2Satuan: data.outcome2_sat,
                                outcome2Nilai: data.outcome2_val,
                            }],
                            outcome3: [{
                                outcome3Satuan: data.outcome3_sat,
                                outcome3Nilai: data.outcome3_val,
                            }],
                        }
                        sessionStorage.setItem('oGIAT_' + data.template_ogiat_id, JSON.stringify(outputKegiatanItems));
                    })


                    // 
                    $.ajax({
                        url: "<?php echo site_url('renstra/total-paket/') ?>" + res.dokumen.id,
                        type: 'GET',
                        success: (res) => {
                            res.paket.forEach((data, index) => {
                                var totalPaketElement = $('[data-rowid="' + data.template_ogiat_id + '"]').find('.totalpaket');
                                totalPaketElement.html(data.total);
                            })
                        },
                        fail: (xhr) => {
                            alert("Terjadi kesalahan pada sistem")
                            console.log(xhr)
                        }
                    })

                    params.beforeModalMount(res)

                    if (res.dokumen.notes_reject != null) {

                        $('.container-revision-alert').removeClass("d-none");

                        $('.container-revision-alert').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Pesan !</h5>
                                <p>${res.dokumen.notes_reject}</p>
                            </div>
                        `)
                    } else {

                        $('.container-revision-alert').html('')
                    }




                    $('.modal').trigger('click');
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

                    elementInput_target.val(formatRupiah(data.target_value.toString().replaceAll('.', ',')))
                    elementInput_target_satuan.val(data.target_sat)
                    elementInput_outcome.val(formatRupiah(data.outcome_value.toString().replaceAll('.', ',')))


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
                    url: "<?php echo site_url('renstra/change-status') ?>",
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
            url: "<?php echo site_url('renstra/change-status') ?>",
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
        let renstra_data_rows = {
            row_indikator: [],
        }

        let tahunForEditStored = localStorage.getItem('tahunForEdit');



        $('.__inputTemplateRow-target').each((key, element) => {
            let elementInput_target = $(element),
                elementInput_target_satuan = elementInput_target.data("targetsatuan"),
                elementInput_outcome = $('.__inputTemplateRow-outcome').eq(key),
                elementInput_outcome_satuan = elementInput_outcome.data("outcome1satuan"),
                element_checkRow = $('input:checkbox[name=form-check-row-indikator]').eq(key)

            let rowIndikator = {
                row_id: elementInput_target.data('row-id'),
                tahun: tahunForEditStored ?? $('#tahunAnggaran').val(),
                target: elementInput_target.val(),
                target_satuan: elementInput_target_satuan,
                outcome1: elementInput_outcome.val(),
                outcome1_satuan: elementInput_outcome_satuan,
                isChecked: elementInput_outcome.val() > 0 || elementInput_target.val() > 0 ? '1' : '0',
            };

            // Ambil nilai dari elementInput_target
            let rowId = elementInput_target.data('row-id');

            // Tambahkan rowIndikator ke dalam renstra_data_rows.row_indikator
            renstra_data_rows.row_indikator.push(rowIndikator);

        });

        let outputkegiatan = []
        let paket = []
        // Buat pola yang ingin dicari di dalam sessionStorage key
        let oGiatSearch = 'oGIAT_';
        let paketSearch = 'Paket_';
        // Gunakan Object.keys untuk mendapatkan semua key dan filter untuk mencari yang cocok
        let oGiatMatch = Object.keys(sessionStorage).filter(key => key.includes(oGiatSearch));
        let paketMatch = Object.keys(sessionStorage).filter(key => key.includes(paketSearch));

        // // Dorong item yang sesuai dari sessionStorage ke dalam rowIndikator.Outputkegiatan
        oGiatMatch.forEach(key => {
            outputkegiatan.push(JSON.parse(sessionStorage.getItem(key)));
        });

        // Dorong item yang sesuai dari sessionStorage ke dalam rowIndikator.paket
        paketMatch.forEach(key => {
            paket.push(JSON.parse(sessionStorage.getItem(key)));
        });

        let inputValue = {
            csrf_test_name: $('input[name=csrf_test_name]').val(),
            templateID: element_btnSaveDokumen.data('template-id'),
            rows: renstra_data_rows,
            ogiat: outputkegiatan,
            paket: paket,
            tahun: tahunForEditStored ?? $('#tahunAnggaran').val()
        }

        return inputValue
    }

    function saveDokumenValidation() {
        // let checkInputKegiatanAnggatan = true,
        //     checkInputKegiatanManual = true,
        //     checkInputTarget = false,
        //     checkInputOutcome1 = false,
        //     checkOutputKegiatan = true
        let checkOgiat = false,
            indikator = '';
        checkPaket = false
        // let anyChecked = false;
        // $(document).find('.__inputTemplateRow-target').each((i, e) => {
        //     if ($(e).val() > 0) {
        //         anyChecked = true;
        //         checkInputTarget = true
        //         checkInputOutcome1 = true

        //     }
        // })
        // $(document).find('.__inputTemplateRow-outcome').each((i, e) => {
        //     if ($(e).val() > 0) {
        //         anyChecked = true;
        //         checkInputTarget = true
        //         checkInputOutcome1 = true
        //     }
        // })


        $('input[name="form-check-row-output-kegiatan"]').each(function(e) {
            if ($(this).prop('checked')) {
                checkOgiat = true; // If any checkbox is checked, set isFormValid to true

                return false; // Exit loop early since we found a checked checkbox
            }
        });


        $('.paket').each((index, element) => {

            let element_rowParent = $(element).parents('tr').find('td'),
                checlist = element_rowParent.find('input:checkbox[name=form-check-row-output-kegiatan]').is(':checked')

            if (checlist) {
                if ($(element).find('.totalpaket').text() > 0) {
                    checkPaket = true
                } else {
                    indikator = $(element).data("indikator");
                    checkPaket = false
                }
            }

        })


        if (checkPaket == false) {
            Swal.fire(
                'Peringatan',
                'Terdapat paket yang belum dipilih pada Output Kegiatan <b>' + indikator + '</b>',
                'warning'
            )
            return false
        }




        if (checkOgiat == false) {
            Swal.fire(
                'Peringatan',
                'Tidak ada output kegiatan yang dipilih',
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
        target: '',
        tahunForEdit: ''
    }) {


        element_btnSaveDokumen.attr('data-template-id', params.templateId)
        element_modalDialog.addClass('modal-xl')
        element_modalFooter.removeClass('d-none')
        element_modalFormChooseTemplate.addClass('d-none')
        element_modalFormMakeDokumen.removeClass('d-none')
        element_modalFormBackChooseTemplate.removeClass('d-none')
        if (params.hasOwnProperty('templateTitle')) {
            element_modalFormTitle.html(`
                <h6>INPUT RENSTRA TAHUN ${$('#tahunAnggaran').val()}</h6>
                <small>${params.templateTitle}</small>
            `)
        }



        renderFormTemplate(params.dataId, params.data, params.target, params.tahunForEdit)
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

        element_btnSaveEditDokumen.attr('id', params.dokumenID)
        // element_btnSaveEditDokumen.attr('data-dokumen-master-id', params.dokumenMasterID)

        element_btnSaveDokumen.addClass('d-none')
        element_btnSaveEditDokumen.removeClass('d-none')
        element_btnSaveEditDokumen.addClass(buttonType)
        element_btnSaveEditDokumen.text(buttonText)
    }

    function render_reset_btnSubmitToRevision() {
        element_btnSaveEditDokumen.removeAttr('data-id')
        // element_btnSaveDokumen.removeAttr('data-dokumen-id')
        // element_btnSaveDokumen.removeAttr('data-dokumen-master-id')

        element_btnSaveEditDokumen.removeClass('btn-danger')
        element_btnSaveEditDokumen.removeClass('btn-warning')
        // element_btnSaveDokumen.removeClass('btn-warning')
        // element_btnSaveDokumen.text('Simpan Dokumen')
    }

    function renderFormTemplate(_dataId, _data, _target, tahunForEdit) {
        last_dokumen_id = '';
        if (_target == 'create' && _data.dokumenExistSameYear != null) {
            paramsBtnPaket = "edit";
            last_dokumen_id = _data.dokumenExistSameYear.last_dokumen_id;
        }



        let template = _data.template,
            value_dataId = _dataId ?? last_dokumen_id,
            templateExtraData = _data.templateExtraData,
            render_rowsForm = renderFormTemplate_rowTable(_data, _data.template.type, _data.satkerid, value_dataId, tahunForEdit),
            // render_rowKegiatan = renderFormTemplate_rowKegiatan(_data.templateKegiatan),
            // render_rowKegiatan = renderFormTemplate_rowOgiat(_data.ogiat, _data.template.type, _data.satkerid),
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

        if (_data.template.type == 'eselon1' || _data.template.type == 'master-balai') {
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


            theadBalaiTarget = '<td class="text-center" style="width:15%">Target ' + tahunForEdit + ' < /td>';
        } else {
            titleTheadTable = '<td class="text-center" style="width:15%">Output ' + tahunForEdit + '</td>';
        }


        localStorage.setItem('tahunForEdit', tahunForEdit);

        let render = `
            <input type="hidden" name="revision_same_year" value="${inputValue_revisionSameYear}" />

            <div class="container-revision-alert">
                ${render_warningDokumenYearRevisoin}
            </div>
        
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="sticky-header-1">
                        
                        <td class="text-center" colspan="3">Sasaran Program / Sasaran Kegiatan / Indikator</td>
                        <!-- <td class="text-center">Output Kegiatan</td> -->
                        ${titleTheadTableOutcomeBalai}


                        ${titleTheadTable}
                  

                        ${theadBalaiTarget}
                        <td class="text-center ${classDNoneOutcome}">
                            Outcome
                        </td>
                        <!-- <td class="text-center ${classDNoneOutcome}">
                            Outcome2
                        </td>
                         <td class="text-center ${classDNoneOutcome}">
                            Outcome3
                        </td> -->

                        

                    </tr>
                    <tr style="font-size:10px" class="sticky-header-2">
                        <td class="text-center p-2 align-middle">
                           <!-- <input type="checkbox" name="form-checkall-row-indikator" checked /> -->
                        </td>
                        <td class="text-center p-2" colspan="2">(1)</td>
                        <td class="text-center p-2">(2)</td>
                        <!-- <td class="text-center p-2">(3)</td> -->
                        ${theadBalaiTargetNumber}
                        <td class="text-center p-2 ${classDNoneOutcome}">(3)</td>
                    </tr> 
                </thead>
                <tbody>
                    ${ render_rowsForm }
                 
                </tbody>
            </table>
     
          

            <div class="container-revision-alert-bottom">
            </div>


            
        `

        $('#make-dokumen').html(render)

        $('select.select2').select2();
        $('input:checkbox[name=form-checkall-row-output-kegiatan]').trigger('click');

    }

    function renderFormTemplate_rowTable(_data, _templateType, _satkerId, DocID, _tahun) {



        let selectedYear = $('#tahunAnggaran').val();
        let rows = '',
            rowNumber = 1,
            ogiatNumber = 1,
            colspanSectionTitle = 4,
            classDNoneOutcome = '',
            data_value = ''

        if (
            _templateType == 'eselon1' ||
            // _templateType == 'eselon2' ||
            _templateType == 'master-balai'
        ) {
            colspanSectionTitle = 2
            classDNoneOutcome = 'd-none'
        }

        _data.templateRow.forEach((data, key) => {
            switch (data.type) {
                case 'section_title':
                    rowNumber = 1
                    ogiatNumber = 1
                    if (data.prefix_title == 'full') {
                        rows += `
                            <tr>
                                <td colspan="${colspanSectionTitle + 2}" >
                                    <strong>${ data.title }</strong>
                                </td>
                            </tr>
                        `
                    } else {
                        rows += `
                            <tr>
                                 <td>
                                  <!-- <input type="checkbox" name="form-checkall-row-indikator" checked /> -->
                                </td> 
                                <td >
                                    <strong>${ data.prefix_title ?? '-' }</strong>
                                </td >
                                <td colspan="${colspanSectionTitle}" >
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
                                <div class="input-group mr-3 ">
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
                            <td >
                                <div class="input-group mr-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ formatRupiah(data.outcomeSatkerValue.toString())}</span>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan }</span>
                                    </div>
                                </div>
                            </td>
                            <td >
                                <div class="input-group  input-group-sm">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-target" 
                                        placeholder="Masukkan Nilai"
                                        value="${ data.targetBalaiDefualtValue }"
                                        data-row-id="${ data.id }"
                                        onkeyup="return this.value = formatRupiah(this.value, '')"
                                    data-pktype="balai" readonly="true" >
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan }</span>
                                    </div>
                                </div>
                            </td>
                        `
                    } else {
                        renderInputTarget = `
                        <td >
                            <div class="input-group  input-group-sm">
                                <input 
                                    type="text" 
                                    class="form-control __inputTemplateRow-target" 
                                    placeholder="Masukkan Nilai"
                                    value="${ data.outcomeSatkerValue }"
                                    data-row-id="${ data.id }"
                                    data-targetSatuan = "${ data.target_satuan.split(';')[0]}"
                                    onkeyup="return this.value = formatRupiah(this.value, '')" data-pktype="satker"
                                    ${data.template_id === '1' || data.template_id === '2'  || data.template_id === '3' || data.template_id === '4' ? 'readonly' :'' } 
                                    
                                    >
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.target_satuan.split(';')[0]}</span>
                                    </div>
                            </div>
                        </td>
                        `
                    }



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
                        <tr data-row-id="${data.id}">
                           <td class="text-center align-middle" width="50px">
                               <!-- <input type="checkbox" name="form-check-row-indikator" checked data-id="${data.id}"/> -->
                            </td>
                            <td class="align-middle" width="10px">${ rowNumber++ }</td>
                            <td class="align-middle">${ data.title }</td>
                             <!-- <td class="text-center">
                            <button class="font-weight-bold btn-light-success btn-sm mr-2 btnOutputKegiatan" 
                            title="pilih output kegiatan" 
                            data-dokid="${DocID || 0}" 
                            data-templateid="${data.template_id}" 
                            data-indikator="${data.title}" 
                            data-rowid="${data.id}" 
                            data-outputsatuan="${data.target_satuan}" 
                            data-outcomesatuan="${data.outcome_satuan}" 
                            data-tahun="${selectedYear}"
                           data-satkerid="${_satkerId}">
                        Output Kegiatan
                        <span class="label label-sm label-white ml-2 totalbtnOutputKegiatan" data-rowid="${data.id}">
                        ${paramsBtnPaket === "uptBalai-add" ? selectedItems.length : data.paket.length}
                        </span>
                    </button>
                            
                            </td> -->
                            `;

                    rows += `</td>

                            ${renderInputTarget}
                            
                            <td class="${classDNoneOutcome} ">
                                <div class="input-group  input-group-sm">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-outcome" 
                                        placeholder="Masukkan Nilai"
                                        value="${ data.outcomeDefaultValue }"
                                        data-row-id="${ data.id }"
                                        data-outcome1satuan="${ data.outcome_satuan }"
                                        onkeyup="return this.value = formatRupiah(this.value, '')"
                                        ${data.template_id === '1' || data.template_id === '2'  || data.template_id === '3' || data.template_id === '4' ? 'readonly' :""}
                                        >
                                    <div class="input-group-append">
                                        <span class="input-group-text">${ data.outcome_satuan }</span>
                                    </div>
                                    
                                </div>
                            </td>
                        </tr>
                       
                    `;

                    if (data.status == "last") {
                        rows += `
                    <tr>
                    <td></td>
                      <td class="text-center p-2 align-middle">
                            <input class="d-none" type="checkbox" name="form-checkall-row-output-kegiatan" checked/>
                        </td>

                    <td colspan="3">
                    <b> OUTPUT KEGIATAN : </b> 
                    </td>
                    </tr>`;


                        ogiatNumber = 1;
                        _data.ogiat.forEach((dataOgiat, key) => {


                            if (dataOgiat.grup == data.grup) {

                                rows += `
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="align-middle" colspan="${colspanSectionTitle}"><strong>${ ogiatNumber++ }. ${ dataOgiat.title } </strong></td>
                            </tr>
                           <tr class="ogiat-row" data-parent-rowid="${dataOgiat.id}">
                                <td></td>
                                <td>
                                  <input type="checkbox" name="form-check-row-output-kegiatan" style="margin-left: 8px !important" data-parent-rowid="${dataOgiat.id}"/>
                                </td>
                                 <td class="align-middle ml-2">${ dataOgiat.title2} 
                                    <button class="font-weight-bold btn-light-success btn-sm mr-2 paket" 
                                        title="pilih paket" 
                                        data-dokid="" 
                                        data-templateid="" 
                                        data-indikator="${dataOgiat.title2}" 
                                        data-rowid="${dataOgiat.id}"
                                        data-outputsatuan="${dataOgiat.satuan_output}" 
                                        data-outcome1satuan="${dataOgiat.satuan_outcome1}"
                                        data-outcome2satuan="${dataOgiat.satuan_outcome2}"
                                        data-outcome3satuan="${dataOgiat.satuan_outcome3}"
                                        data-satkerid="${_satkerId}"
                                        data-tahun="${_tahun ?? selectedYear}">
                                        Paket <span class="label label-sm label-white ml-2 totalpaket">0</span>
                                    </button>
                                 </td>`;
                                //Target
                                const output_satuan = dataOgiat.satuan_output.split(';');
                                rows += `<td class="output">`;
                                output_satuan.forEach(output => {

                                    rows += ` <div class="input-group input-group-sm mt-1">
                                        <input 
                                            type="text" 
                                            class="form-control __targetValue __targetValue-${output.replace(/\s+/g, '')}" 
                                            placeholder="0"
                                            data-row-id="${dataOgiat.id}"
                                            data-targetSatuan = "${output}"
                                            readonly="true"
                                            >
                                            <div class="input-group-append">
                                               <span class="input-group-text  bg-warning" >
                                                ${output}
                                                </span>
                                            </div>
                                        </div>`;
                                });
                                rows += `</td>`;

                                //Outcome1
                                const outcome1_satuan = dataOgiat.satuan_outcome1.split(';');

                                if (dataOgiat.id == "OTKSK503704" || dataOgiat.id == "OTKSK503706") {

                                    rows += `<td class="outcome1">`;
                                    outcome1_satuan.forEach(outcome => {

                                        var cleanedOutcome = outcome.replace(/\s+/g, '').replace('%', 'percent').replace(/\//g, '');;
                                        rows += ` <div class="input-group input-group-sm mt-1">
                                        <input 
                                            type="text" 
                                            class="form-control __outcome1Value __outcome1Value-${cleanedOutcome}" 
                                            placeholder="0"
                                            data-row-id="${dataOgiat.id}"
                                            data-outcomeSatuan = "${outcome}"
                                           >

                                        <input type="hidden" class="HektarKawasanPantura" data-row-id="${dataOgiat.id}">
                                            <div class="input-group-append">
                                               <span class="input-group-text  bg-warning">
                                                ${outcome}
                                                </span>
                                            </div>
                                        </div>`;
                                    });
                                    rows += `</td>`;


                                } else {
                                    rows += `<td class="outcome1">`;
                                    outcome1_satuan.forEach(outcome => {
                                        var cleanedOutcome = outcome.replace(/\s+/g, '').replace('%', 'percent').replace(/\//g, '');;
                                        rows += ` <div class="input-group input-group-sm mt-1">
                                        <input 
                                            type="text" 
                                            class="form-control __outcome1Value __outcome1Value-${cleanedOutcome}" 
                                            placeholder="0"
                                            data-row-id="${dataOgiat.id}"
                                            data-outcomeSatuan = "${outcome}"
                                            >
                                            <div class="input-group-append">
                                               <span class="input-group-text  bg-warning">
                                                ${outcome}
                                                </span>
                                            </div>
                                        </div>`;
                                    });
                                    rows += `</td>`;


                                }



                                //Outcome2
                                if (dataOgiat.satuan_outcome2) {
                                    const outcome2_satuan = dataOgiat.satuan_outcome2.split(';');

                                    rows += `<td class="outcome2">`;
                                    outcome2_satuan.forEach(outcome => {
                                        var cleanedOutcome2 = outcome.replace(/\s+/g, '').replace('%', 'percent').replace(/\//g, '');;

                                        rows += ` <div class="input-group input-group-sm mt-1">
                                        <input 
                                            type="text" 
                                            class="form-control __outcome2Value __outcome2Value-${cleanedOutcome2}" 
                                            placeholder="0"
                                            data-row-id="${dataOgiat.id}"
                                            data-outcomeSatuan = "${outcome}">
                                            <div class="input-group-append">
                                               <span class="input-group-text  bg-warning">
                                                ${outcome}
                                                </span>
                                            </div>
                                        </div>`;
                                    });
                                    rows += `</td>`;
                                }

                                //Outcome3
                                if (dataOgiat.satuan_outcome3) {
                                    const outcome3_satuan = dataOgiat.satuan_outcome3.split(';');

                                    rows += `<td>`;
                                    outcome3_satuan.forEach(outcome => {
                                        var cleanedOutcome3 = outcome.replace(/\s+/g, '').replace('%', 'percent').replace(/\//g, '');;

                                        rows += ` <div class="input-group input-group-sm mt-1">
                                        <input 
                                            type="text" 
                                            class="form-control __outcome3Value __outcome3Value-${cleanedOutcome3}" 
                                            placeholder="0"
                                            data-row-id="${dataOgiat.id}"
                                            data-outcomeSatuan = "${outcome}">
                                            <div class="input-group-append">
                                               <span class="input-group-text  bg-warning">
                                                ${outcome}
                                                </span>
                                            </div>
                                        </div>`;
                                    });
                                    rows += `</td>`;
                                }


                                rows += ` </tr>`;


                            }




                        });










                    }



                    //         if (data.ogiat.length > 0) {

                    //             data.ogiat.forEach((dataOgiat, key) => {


                    //                 rows += `
                    //    <tr class="ogiat-row" data-parent-rowid="${data.id}">
                    //                 <td colspan="2" class="text-center align-middle" width="50px"></td>
                    //                 <td class="align-middle" colspan="${colspanSectionTitle}">${ ogiatNumber++ }. ${ dataOgiat.title } </td>

                    //             </tr>
                    //        <tr class="ogiat-row" data-parent-rowid="${data.id}">
                    //              <td colspan="2" class="text-center align-middle" width="50px"></td>
                    //              <td class="align-middle" > ${ dataOgiat.title2} </td>


                    //              <td>
                    //                 <div class="input-group">
                    //                     <input 
                    //                         type="text" 
                    //                         class="form-control __inputTemplateRow-target" 
                    //                         placeholder="Masukkan Nilai"
                    //                         value="${ data.outcomeSatkerValue }"
                    //                         data-row-id="${ data.id }"
                    //                         data-targetSatuan = "${ data.target_satuan}"
                    //                         onkeyup="return this.value = formatRupiah(this.value, '')" data-pktype="satker"
                    //                         ${data.template_id === '5' || data.template_id === '6'|| data.template_id === '9'  || data.template_id === '11' || data.template_id === '12' || data.template_id === '13'  || data.template_id === '14' || data.template_id === '15'  || data.template_id === '16'|| data.template_id === '17' || data.template_id === '18' || data.template_id === '19'|| data.template_id === '20' || data.template_id === '29' || _templateType === 'eselon2' ||  _tahun === '2023' ? '' :'readonly' }>
                    //                         <div class="input-group-append">
                    //                             <span class="input-group-text">${ dataOgiat.satuan_output.split(';')[0]}</span>
                    //                         </div>
                    //                 </div>
                    //             </td>
                    //             <td class="${classDNoneOutcome}">
                    //                     <div class="input-group">
                    //                         <input 
                    //                             type="text" 
                    //                             class="form-control __inputTemplateRow-outcome" 
                    //                             placeholder="Masukkan Nilai"
                    //                             value="${ data.outcomeDefaultValue }"
                    //                             data-row-id="${ data.id }"
                    //                             onkeyup="return this.value = formatRupiah(this.value, '')"
                    //                             ${data.template_id === '5' || data.template_id === '6'  || data.template_id === '9' ||data.template_id === '11' || data.template_id === '12' || data.template_id === '13'  || data.template_id === '14' || data.template_id === '15'  || data.template_id === '16'|| data.template_id === '17' || data.template_id === '18' || data.template_id === '19'|| data.template_id === '20' || data.template_id === '29' || _templateType === 'eselon2' ||  _tahun === '2023' ? '' :"readonly"}
                    //                             >
                    //                         <div class="input-group-append">
                    //                             <span class="input-group-text">${ dataOgiat.satuan_outcome1.split(';')[0] }</span>
                    //                         </div>
                    //                     </div>
                    //                 </td>


                    //             </tr>



                    //         `


                    //             });



                    //         }




                    break;




            }


        });




        return rows;
    }

    function renderFormTemplate_rowKegiatan(_data) {
        let list = ''

        _data.forEach((data, key) => {
            list += renderFormTemplate_rowKegiatan_item({
                id: data.id,
                nama: data.nama,
                rowType: 'text'
            })
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

        return renderOptions
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
</script>
<!-- modal render pilih paket //paket -->

<script>
    $(document).on('click', '.paket', function() {
        $('#modalPilihPaket').modal('show');
        $('.modal.btn-modal-full').trigger('click');

        let satkerId = $(this).data('satkerid');
        let templateId = $(this).data('templateid');

        var balaiCreateSatker = $(".__opsi-template").attr("data-balai-create-satker");

        // // console.log(balaiCreateSatker);


        // if (balaiCreateSatker != undefined) {

        //     satkerId = balaiCreateSatker;

        // }


        let indikator = $(this).data('indikator');
        let _tahun = $(this).data('tahun');


        // kode satker 498077 = PJSA BATANGHARI 2024 ganti kode ke 633074
        // kode satker 498366 = Bendungan Cimanuk 2024 ganti kode ke 690690
        if (satkerId == "633074" &&
            _tahun < 2024) {

            satkerId = "498077"
        }


        if (satkerId == "690680" &&
            _tahun < 2024) {
            satkerId = "498366"
        }









        let docId = $(this).data('dokid');
        let indikatorID = $(this).attr('data-rowid');
        // let skindikatorID = $(this).attr('data-indikatorid');
        // let output_satuan = $('.select-target-satuan[data-row-id=' + indikatorID + ']').val();
        let output_satuan = $(this).data('outputsatuan');
        let outcome1_satuan = $(this).data('outcome1satuan');
        let outcome2_satuan = $(this).data('outcome2satuan');
        let outcome3_satuan = $(this).data('outcome3satuan');
        $('#modalFormTitlePaket').html(``);
        $('#modalFormTitlePaket').html(`<h6>Pilih Paket Tahun ${_tahun}</h6><small>Indikator : <b>${ indikator } </b></small>`);

        $(document).find('.save-btn-paket').removeAttr("data-indikatorid");
        $(document).find('.save-btn-paket').removeAttr("data-satkerid");
        $(document).find('.save-btn-paket').removeAttr("data-skindikatorid");
        $(document).find('.save-btn-paket').attr("data-indikatorid", indikatorID)
        $(document).find('.save-btn-paket').attr("data-satkerid", satkerId)
        // $(document).find('.save-btn-paket').attr("data-skindikatorid", skindikatorID)

        //get paket
        $.ajax({
            url: "<?php echo site_url('api/getpaket') ?>",
            type: 'GET',
            data: {
                satkerId: satkerId,
                templateId: templateId,
                _tahun: _tahun
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
                            <td colspan = "12"><strong>${balai.balai}</strong></td>
                            </tr>`);
                        }
                        tbody.append(`
                            <tr style="background-color:#b6dced" class="sticky-header-3">
                            <td><strong>${balai.satkerid}</strong></td>
                            <td colspan = "12"><strong>${balai.satker}</strong></td>
                            </tr>
                           `);

                        balai.paket.forEach(function(paket, index) {
                            var selectedItems = [];
                            $('tr[data-row-id]').each(function() {
                                let skindikatorID = $(this).data('row-id')
                                var storedItems = sessionStorage.getItem("Paket_" + indikatorID)
                                if (storedItems) {
                                    selectedItems = JSON.parse(storedItems);
                                }
                            })
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

                            $(".outcome2").addClass('d-none');
                            $(".outcome3").addClass('d-none');

                            tbodyContent = `<tr ${trClass}>
                                  <td width="5%">${checkboxHtml}</td>
                                    <td width="10%">${paket.paketId}</td>
                                    <td width="20%">${paket.label}</td>
                                    <td width="5%">${paket.vol}</td>
                                    <td width="5%">${paket.satuan}</td>
                                    <!-- <td>${paket.paguDipa}</td>
                                    <td>${paket.realisasi}</td> -->
                                    <td width="5%">${paket.persenKeu}</td>
                                    <td width="5%">${paket.persenFis}</td>
                                    <td width="10%">`;
                            // if (output_satuan !== "DI") {

                            tbodyContent += `<div class="form-group form-group-last row">
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
                                    </div>`;
                            // }




                            tbodyContent += `</td>
                                    <td width="10%">
                                    <div class="form-group form-group-last row">
                                        <div class="form-group-sub">
                                            <label class="form-control-label center">Vol Outcome :</label>
                                            <input type="text" class="form-control outcome1_nilai checkbox-click" name="outcome1_nilai" placeholder="" onkeyup="return this.value = formatRupiah(this.value, '')" ${selectedItems.some(item => item.paketId === paket.paketId)? "value=" +selectedItems.find(item => item.paketId === paket.paketId).outcome1_nilai:"disabled"}                        
                                           >
                                        </div>
                                        <div class="form-group-sub">
                                            <label class="form-control-label">Satuan Outcome :</label>
                                        
                                            <div class="input-group-append">
                                                    <select class="form-control checkbox-click" ${selectedItems.some(item => item.paketId === paket.paketId) ? "value=" +selectedItems.find(item => item.paketId === paket.paketId).outcome_nilai:"disabled"} name="outcome1_satuan">
                                                        ${outcome1_satuan.split(';').map(function(satuan) {
                                                            const isSelected = selectedItems.some(item => item.paketId === paket.paketId && item.outcome1_satuan === satuan.trim());
                                                            return `<option value="${satuan.trim()}" ${isSelected ? 'selected' : ''}>${satuan.trim()}</option>`;
                                                        }).join('')}
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    </td>`;

                            if (outcome2_satuan) {
                                $(".outcome2").removeClass('d-none');


                                if (outcome2_satuan !== "MW") {
                                    tbodyContent += `<td class="text-center" style="vertical-align: middle; height: 100%;" width="10%">
                                                <select class="form-control checkbox-click" ${selectedItems.some(item => item.paketId === paket.paketId)? selectedItems.find(item => item.paketId === paket.paketId).outcome2_nilai:"disabled"} name="outcome2_satuan">
                                                        <option value="">Pilih Tipe</option>
                                                        ${outcome2_satuan.split(';').map(function(satuan) {
                                                            const isSelected = selectedItems.some(item => item.paketId === paket.paketId && item.outcome2_satuan === satuan.trim());
                                                            return `<option value="${satuan.trim()}" ${isSelected ? 'selected' : ''}>${satuan.trim()}</option>`;
                                                        }).join('')}
                                                    </select>
                                                    </td>`;
                                } else {
                                    tbodyContent += `<td width="10%">
                                    <div class="form-group form-group-last row">
                                        <div class="form-group-sub">
                                            <label class="form-control-label center">Vol Outcome :</label>
                                            <input type="text" class="form-control outcome2_nilai checkbox-click" name="outcome2_nilai" placeholder="" onkeyup="return this.value = formatRupiah(this.value, '')" ${selectedItems.some(item => item.paketId === paket.paketId)? "value=" +selectedItems.find(item => item.paketId === paket.paketId).outcome2_nilai:"disabled"}>
                                        </div>
                                        <div class="form-group-sub">
                                            <label class="form-control-label">Satuan Outcome :</label>
                                        
                                            <div class="input-group-append">
                                                    <select class="form-control checkbox-click" ${selectedItems.some(item => item.paketId === paket.paketId) ? "value=" +selectedItems.find(item => item.paketId === paket.paketId).outcome2_nilai:"disabled"} name="outcome2_satuan">
                                                        ${outcome2_satuan.split(';').map(function(satuan) {
                                                            const isSelected = selectedItems.some(item => item.paketId === paket.paketId && item.outcome2_satuan === satuan.trim());
                                                            return `<option value="${satuan.trim()}" ${isSelected ? 'selected' : ''}>${satuan.trim()}</option>`;
                                                        }).join('')}
                                                    </select>
                                                </div>
                                        </div>
                                    </div>
                                    </td>`

                                }


                            }

                            if (outcome3_satuan) {
                                $(".outcome3").removeClass('d-none');

                                tbodyContent += `<td class="text-center" style="vertical-align: middle; height: 100%;" width="10%">
                                                <select class="form-control checkbox-click" ${selectedItems.some(item => item.paketId === paket.paketId)? selectedItems.find(item => item.paketId === paket.paketId).outcome3_nilai:"disabled"} name="outcome3_satuan">
                                                        <option value="">Pilih Kategori</option>
                                                        ${outcome3_satuan.split(';').map(function(satuan) {
                                                            const isSelected = selectedItems.some(item => item.paketId === paket.paketId && item.outcome3_satuan === satuan.trim());
                                                            return `<option value="${satuan.trim()}" ${isSelected ? 'selected' : ''}>${satuan.trim()}</option>`;
                                                        }).join('')}
                                                    </select>
                                                    </td>`;

                            }
                            tbodyContent += `</tr>`;
                            tbody.append(tbodyContent);

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
        indikatorId = $(this).attr('data-indikatorid');
        skindikatorId = $(this).attr('data-skindikatorid');
        satkerId = $(this).attr('data-satkerid');


        var selectedItems = [];
        const outputKegiatanItems = {
            oGiatId: [],
            target: [],
            outcome1: [],
            outcome2: [],
            outcome3: [],
        };

        var errorMessages = [];
        var totalJumlahTarget = 0;
        var totalJumlahOutcome1 = 0;
        var totalJumlahOutcome2 = 0;
        var totalJumlahOutcome3 = 0;
        var TargetlengthFix = 2;
        var Outcome1lengthFix = 2;
        var Outcome2lengthFix = 2;
        var Outcome3lengthFix = 2;
        var targetTotals = {};
        var outcome1Totals = {};
        var outcome2Totals = {};
        var outcome3Totals = {};
        var hektarKawasanPantura = 0;

        $('.checkbox:checked').each(function() {
            var paketId = $(this).attr('val');
            var target_nilai = $(this).closest('tr').find('input[name=target_nilai]').val();
            var target_satuan = $(this).closest('tr').find('select[name=target_satuan]').val();
            var outcome1_nilai = $(this).closest('tr').find('input[name=outcome1_nilai]').val();
            var outcome1_satuan = $(this).closest('tr').find('select[name=outcome1_satuan]').val();

            var outcome2_nilai = $(this).closest('tr').find('input[name=outcome2_nilai]').val();
            var outcome2_satuan = !$('.outcome2').hasClass('d-none') ? $(this).closest('tr').find('select[name=outcome2_satuan]').val() : '';


            var outcome3_nilai = $(this).closest('tr').find('input[name=outcome3_nilai]').val();
            var outcome3_satuan = !$('.outcome3').hasClass('d-none') ? $(this).closest('tr').find('select[name=outcome3_satuan]').val() : '';
            // console.log(outcome2_satuan)
            if (!$('.outcome2').hasClass('d-none') && satkerId != '403477' && outcome2_satuan.trim() == "MW") {

                if (outcome2_nilai.trim() === '') {
                    errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Tipe Vol yang belum diisi.');
                }

            }


            // jika satuan target DI (PJPA)
            // if (target_satuan != undefined) {
            //     if (target_nilai.trim() === '') {
            //         errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Target Nilai yang belum diisi.');
            //     } else if (target_satuan.trim() === '') {
            //         errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Target Satuan yang belum diisi.');
            //     }
            // } else {
            //     target_nilai = "0";
            //     target_satuan = "DI";

            // }




            if (outcome1_nilai.trim() === '') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Outcome1 Nilai yang belum diisi.');
            }

            // else if (!$('.outcome2').hasClass('d-none') && outcome2_nilai.trim() === '') {
            //     errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Outcome2 Nilai yang belum diisi.');
            // }
            // else if (!$('.outcome3').hasClass('d-none') && outcome3_nilai.trim() === '') {
            //     errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Outcome3 Nilai yang belum diisi.');
            // } 
            else if (outcome1_satuan.trim() === '') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Outcome1 Satuan yang belum diisi.');
            } else if (!$('.outcome2').hasClass('d-none') && outcome2_satuan.trim() === '' && satkerId != '403477') {

                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Tipe Satuan yang belum diisi.');

            } else if (!$('.outcome3').hasClass('d-none') && outcome3_satuan.trim() === '' && satkerId != '403477') {
                errorMessages.push('Paket dengan ID ' + paketId + ' memiliki Kategori Satuan yang belum diisi.');
            } else {

                selectedItems.push({
                    oGiatId: indikatorId,
                    paketId: paketId,
                    target_nilai: target_nilai,
                    target_satuan: target_satuan,
                    outcome1_nilai: outcome1_nilai,
                    outcome1_satuan: outcome1_satuan,
                    outcome2_nilai: outcome2_nilai ?? "",
                    outcome2_satuan: outcome2_satuan ?? "",
                    outcome3_nilai: outcome3_nilai ?? "",
                    outcome3_satuan: outcome3_satuan ?? ""

                });


                target_nilai_number_remove_titik = target_nilai.replace(/\./g, "");
                outcome1_nilai_number_remove_titik = outcome1_nilai.replace(/\./g, "");
                // outcome2_nilai_number_remove_titik = !$('.outcome2').hasClass('d-none') ? outcome2_nilai.replace(/\./g, "") : 0;
                // outcome3_nilai_number_remove_titik = !$('.outcome3').hasClass('d-none') ? outcome3_nilai.replace(/\./g, "") : 0;

                target_nilai_number = parseFloat(target_nilai_number_remove_titik.replace(',', '.'));
                outcome1_nilai_number = parseFloat(outcome1_nilai_number_remove_titik.replace(',', '.'));


                if (outcome2_satuan !== "MW") {
                    outcome2_nilai_number = !$('.outcome2').hasClass('d-none') ? 1 : 0;
                } else {

                    outcome2_nilai_number_remove_titik = !$('.outcome2').hasClass('d-none') ? outcome2_nilai.replace(/\./g, "") : 0;
                    outcome2_nilai_number = parseFloat(outcome2_nilai_number_remove_titik.replace(',', '.'));

                }


                outcome3_nilai_number = !$('.outcome3').hasClass('d-none') ? 1 : 0;

                if (target_satuan) {
                    if (!targetTotals[target_satuan]) {
                        targetTotals[target_satuan] = 0; // Inisialisasi jika belum ada
                    }
                    targetTotals[target_satuan] += target_nilai_number; // Tambahkan nilai ke total
                }
                if (outcome1_satuan) {
                    if (!outcome1Totals[outcome1_satuan]) {
                        outcome1Totals[outcome1_satuan] = 0; // Inisialisasi jika belum ada
                    }
                    outcome1Totals[outcome1_satuan] += outcome1_nilai_number; // Tambahkan nilai ke total

                    //PJSA Bagian Kawasan Pesisir Utara Jawa
                    if ((indikatorId == "OTKSK503704" || indikatorId == "OTKSK503706" || indikatorId == "OTKSK503709") && outcome2_satuan == "Kawasan Pesisir Utara Jawa") {

                        hektarKawasanPantura += outcome1_nilai_number;

                    }
                }

                if (outcome2_satuan) {


                    if (!outcome2Totals[outcome2_satuan]) {
                        outcome2Totals[outcome2_satuan] = 0; // Inisialisasi jika belum ada
                    }
                    outcome2Totals[outcome2_satuan] += outcome2_nilai_number; // Tambahkan nilai ke total
                }


                if (outcome3_satuan) {
                    if (!outcome3Totals[outcome3_satuan]) {
                        outcome3Totals[outcome3_satuan] = 0; // Inisialisasi jika belum ada
                    }
                    outcome3Totals[outcome3_satuan] += outcome3_nilai_number; // Tambahkan nilai ke total
                }

                // totalJumlahTarget += target_nilai_number;


                if (target_satuan.trim().toLowerCase() === "m3/detik") {

                    TargetlengthFix = 5;
                }

                if (target_satuan.trim().toLowerCase() === "juta m3") {

                    TargetlengthFix = 6;
                }


                if (outcome1_satuan.trim().toLowerCase() === "m3/detik") {

                    Outcome1lengthFix = 5;
                }

                if (outcome1_satuan.trim().toLowerCase() === "juta m3") {

                    Outcome1lengthFix = 6;
                }

                if (!$('.outcome2').hasClass('d-none') && outcome2_satuan.trim().toLowerCase() === "m3/detik") {

                    Outcome2lengthFix = 5;
                }

                if (!$('.outcome2').hasClass('d-none') && outcome2_satuan.trim().toLowerCase() === "juta m3") {

                    Outcome2lengthFix = 6;
                }

                if (!$('.outcome3').hasClass('d-none') && outcome3_satuan.trim().toLowerCase() === "m3/detik") {

                    Outcome3lengthFix = 5;
                }

                if (!$('.outcome3').hasClass('d-none') && outcome3_satuan.trim().toLowerCase() === "juta m3") {

                    Outcome3lengthFix = 6;
                }

                // totalJumlahOutcome1 += outcome1_nilai_number;
                // totalJumlahOutcome2 += outcome2_nilai_number;
                // totalJumlahOutcome3 += outcome3_nilai_number;
            }

        });
        if (errorMessages.length > 0) {
            // Menampilkan pesan kesalahan jika ada
            errorMessages.forEach(function(message) {
                Swal.fire('Peringatan', message, 'warning');
            });
        } else if (selectedItems.length > 0) {
            sessionStorage.setItem("Paket_" + indikatorId, JSON.stringify(selectedItems));


            outputKegiatanItems.oGiatId.unshift({
                oGiatId: indikatorId,
            });
            sessionStorage.setItem("oGIAT_" + indikatorId, JSON.stringify(outputKegiatanItems));

            var totalPaketElement = $('[data-rowid="' + indikatorId + '"]').find('.totalpaket');
            totalPaketElement.html(selectedItems.length);
            $('input[data-row-id="' + indikatorId + '"]').val('');

            Object.entries(targetTotals).forEach(([satuan, total]) => {
                var jumlahDesimal_target = (total % 1 === 0) ? 0 : total.toFixed(TargetlengthFix).toString().split('.')[1].length;

                totalJumlahTargetDenganKoma = total.toLocaleString('id-ID', {
                    // minimumFractionDigits: jumlahDesimal_target
                    minimumFractionDigits: jumlahDesimal_target
                });

                var totalTarget_nilai = $('.__targetValue-' + satuan.replaceAll(/ /g, '') + '[data-row-id=' + indikatorId + ']')



                // if (satuan == "DI") {
                //     totalTarget_nilai.val("1");
                // } else {
                totalTarget_nilai.val(totalJumlahTargetDenganKoma);

                // }


                outputKegiatanItems.target.unshift({
                    // oGiatId: indikatorId,
                    targetSatuan: satuan.replaceAll(/ /g, ''),
                    targetNilai: totalJumlahTargetDenganKoma
                });

                sessionStorage.setItem("oGIAT_" + indikatorId, JSON.stringify(outputKegiatanItems));

            });


            Object.entries(outcome1Totals).forEach(([satuan, total]) => {

                var jumlahDesimal_outcome1 = (total % 1 === 0) ? 0 : total.toFixed(Outcome1lengthFix).toString().split('.')[1].length;

                totalJumlahOutcome1DenganKoma = total.toLocaleString('id-ID', {
                    minimumFractionDigits: jumlahDesimal_outcome1
                });
                var cleanedSatuan = satuan.replaceAll(/ /g, ''); // Menghapus spasi

                // Jika 'satuan' mengandung '%', bersihkan simbolnya
                if (satuan.includes('%')) {

                    cleanedSatuan = cleanedSatuan.replaceAll('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                    average = (total / selectedItems.length); // Gantilah lengthPaketOutcome1 dengan countAverage
                    totalJumlahOutcome1DenganKoma = average.toLocaleString('id-ID', {
                        minimumFractionDigits: jumlahDesimal_outcome1
                    });

                }
                if (satuan.includes('M3/detik')) {
                    cleanedSatuan = cleanedSatuan.replace(/\//g, "");

                }


                var totalOutcome1_nilai = $('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + indikatorId + ']')
                totalOutcome1_nilai.val(totalJumlahOutcome1DenganKoma);
                // $('.__inputTemplateRow-outcome[data-row-id=' + skindikatorId + ']').val(totalJumlahOutcome1DenganKoma)


                //PJSA Bagian Kawasan Pesisir Utara Jawa
                if ((indikatorId == "OTKSK503704" || indikatorId == "OTKSK503706" || indikatorId == "OTKSK503709")) {

                    var jumlahDesimal_outcome1KawasanPantura = (hektarKawasanPantura % 1 === 0) ? 0 : hektarKawasanPantura.toFixed(Outcome1lengthFix).toString().split('.')[1].length;

                    totalJumlahOutcome1PanturaDenganKoma = hektarKawasanPantura.toLocaleString('id-ID', {
                        minimumFractionDigits: jumlahDesimal_outcome1KawasanPantura
                    });


                    var ElementHektarKawasanPantura = $('.HektarKawasanPantura' + '[data-row-id=' + indikatorId + ']')
                    ElementHektarKawasanPantura.val(totalJumlahOutcome1PanturaDenganKoma);

                }



                outputKegiatanItems.outcome1.unshift({
                    outcome1Satuan: satuan.replaceAll(/ /g, ''),
                    outcome1Nilai: totalJumlahOutcome1DenganKoma
                });

                sessionStorage.setItem("oGIAT_" + indikatorId, JSON.stringify(outputKegiatanItems));
            });

            Object.entries(outcome2Totals).forEach(([satuan, total]) => {

                var jumlahDesimal_outcome2 = (total % 1 === 0) ? 0 : total.toFixed(Outcome2lengthFix).toString().split('.')[1].length;

                totalJumlahOutcome2DenganKoma = total.toLocaleString('id-ID', {
                    minimumFractionDigits: jumlahDesimal_outcome2
                });

                var cleanedSatuan = satuan.replaceAll(/ /g, ''); // Menghapus spasi

                // Jika 'satuan' mengandung '%', bersihkan simbolnya
                if (satuan.includes('%')) {
                    cleanedSatuan = cleanedSatuan.replaceAll('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                }
                if (satuan.includes('M3/detik')) {
                    cleanedSatuan = cleanedSatuan.replace(/\//g, "");

                }
                var totalOutcome2_nilai = $('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + indikatorId + ']')
                totalOutcome2_nilai.val(totalJumlahOutcome2DenganKoma);

                outputKegiatanItems.outcome2.unshift({
                    outcome2Satuan: satuan.replaceAll(/ /g, ''),
                    outcome2Nilai: totalJumlahOutcome2DenganKoma
                });

                sessionStorage.setItem("oGIAT_" + indikatorId, JSON.stringify(outputKegiatanItems));
            });

            Object.entries(outcome3Totals).forEach(([satuan, total]) => {
                var jumlahDesimal_outcome3 = (total % 1 === 0) ? 0 : total.toFixed(Outcome3lengthFix).toString().split('.')[1].length;

                totalJumlahOutcome3DenganKoma = total.toLocaleString('id-ID', {
                    minimumFractionDigits: jumlahDesimal_outcome3
                });
                var cleanedSatuan = satuan.replaceAll(/ /g, ''); // Menghapus spasi

                // Jika 'satuan' mengandung '%', bersihkan simbolnya
                if (satuan.includes('%')) {
                    cleanedSatuan = cleanedSatuan.replaceAll('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                }
                if (satuan.includes('M3/detik')) {
                    cleanedSatuan = cleanedSatuan.replace(/\//g, "");

                }
                var totalOutcome3_nilai = $('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + indikatorId + ']')
                totalOutcome3_nilai.val(totalJumlahOutcome3DenganKoma);

                outputKegiatanItems.outcome3.unshift({
                    outcome3Satuan: satuan.replaceAll(/ /g, ''),
                    outcome3Nilai: totalJumlahOutcome3DenganKoma
                });
                sessionStorage.setItem("oGIAT_" + indikatorId, JSON.stringify(outputKegiatanItems));

            });



            let dataID = $(".__buat-dokumen-pilih-template").data('id')
            $("tr[data-row-id]").each(function() {
                let elParent = $(this)
                // console.log(elParent.find('.__inputTemplateRow-target').data('targetsatuan'))
                let row_id = elParent.data('row-id')

                $.ajax({
                    url: "<?php echo site_url('renstra/get-rumus-outcome/') ?>" + dataID + "/" + row_id,
                    type: 'GET',
                    data: {},
                    success: (arr) => {
                        if (arr[row_id] !== undefined) {


                            if (elParent.find('.__inputTemplateRow-target').data('targetsatuan') !== undefined) {
                                let total = 0

                                arr[row_id][elParent.find('.__inputTemplateRow-target').data('targetsatuan')]['parent'].forEach((v) => {
                                    let satuan = arr[row_id][elParent.find('.__inputTemplateRow-target').data('targetsatuan')]['satuan']
                                    var cleanedSatuan = satuan.replace(/ /g, ''); // Menghapus spasi

                                    // Jika 'satuan' mengandung '%', bersihkan simbolnya
                                    if (satuan.includes('%')) {
                                        cleanedSatuan = cleanedSatuan.replace('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                                    }
                                    if (satuan.includes('M3/detik')) {
                                        cleanedSatuan = cleanedSatuan.replace(/\//g, "");

                                    }




                                    let getOgiat = sessionStorage.getItem(`oGIAT_${v}`)
                                    let getOgiatData = JSON.parse(getOgiat)
                                    if (getOgiatData !== null) {


                                        if ($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseFloat($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replace(',', '.'))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }


                                        }
                                        if ($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseFloat($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", "."))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }


                                        if ($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseFloat($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", "."))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }



                                        if ($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseFloat($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", "."))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }
                                    }
                                })


                                var jumlahDesimal_target = (total % 1 === 0) ? 0 : total.toFixed(TargetlengthFix).toString().split('.')[1].length;
                                totalJumlahTargetDenganKomaIndikator = total.toLocaleString('id-ID', {
                                    // minimumFractionDigits: jumlahDesimal_target
                                    minimumFractionDigits: jumlahDesimal_target
                                });

                                $(this).find('.__inputTemplateRow-target[data-row-id=' + row_id + ']').val(totalJumlahTargetDenganKomaIndikator)


                            }


                            if (elParent.find('.__inputTemplateRow-outcome').data('outcome1satuan') !== undefined) {
                                let total1 = 0
                                let total2 = 0
                                arr[row_id][elParent.find('.__inputTemplateRow-outcome').data('outcome1satuan')]['parent'].forEach((v) => {
                                    let satuan = arr[row_id][elParent.find('.__inputTemplateRow-outcome').data('outcome1satuan')]['satuan']
                                    var cleanedSatuan = satuan.replace(/ /g, ''); // Menghapus spasi

                                    // Jika 'satuan' mengandung '%', bersihkan simbolnya
                                    if (satuan.includes('%')) {
                                        cleanedSatuan = cleanedSatuan.replace('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                                    }
                                    if (satuan.includes('M3/detik')) {
                                        cleanedSatuan = cleanedSatuan.replace(/\//g, "");
                                    }

                                    if ($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseFloat($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", '.'))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }
                                    }
                                    if ($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseFloat($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", '.'))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }




                                    }
                                    if ($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseFloat($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", '.'))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }

                                    }
                                    if ($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseFloat($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replace(/\./g, "").replaceAll(",", '.'))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }


                                    }
                                })



                                if (row_id == "21004") {

                                    let nilaiOTKSK503704 = parseFloat($('.HektarKawasanPantura' + '[data-row-id=OTKSK503704]').val().replace(/\./g, "").replaceAll(",", '.'))
                                    let nilaiOTKSK503706 = parseFloat($('.HektarKawasanPantura' + '[data-row-id=OTKSK503706]').val().replace(/\./g, "").replaceAll(",", '.'))

                                    if (!isNaN(nilaiOTKSK503704)) {
                                        total2 += nilaiOTKSK503704
                                    }

                                    if (!isNaN(nilaiOTKSK503706)) {
                                        total2 += nilaiOTKSK503706
                                    }

                                    var jumlahDesimal_target = (total2 % 1 === 0) ? 0 : total2.toFixed(Outcome1lengthFix).toString().split('.')[1].length;
                                    totalJumlahTargetDenganKomaIndikator = total2.toLocaleString('id-ID', {
                                        // minimumFractionDigits: jumlahDesimal_target
                                        minimumFractionDigits: jumlahDesimal_target
                                    });


                                    $(this).find('.__inputTemplateRow-outcome[data-row-id=' + row_id + ']').val(totalJumlahTargetDenganKomaIndikator)

                                } else {
                                    var jumlahDesimal_target = (total1 % 1 === 0) ? 0 : total1.toFixed(Outcome1lengthFix).toString().split('.')[1].length;
                                    totalJumlahTargetDenganKomaIndikator = total1.toLocaleString('id-ID', {
                                        // minimumFractionDigits: jumlahDesimal_target
                                        minimumFractionDigits: jumlahDesimal_target
                                    });

                                    console.log(totalJumlahTargetDenganKomaIndikator);

                                    $(this).find('.__inputTemplateRow-outcome[data-row-id=' + row_id + ']').val(totalJumlahTargetDenganKomaIndikator)
                                }



                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Gagal',
                            icon: "warning",
                            text: 'Rumus Tidak Ditemukan!',
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

            $('#modalPilihPaket').modal('hide');
        } else {
            Swal.fire(
                'Gagal Menyimpan',
                'Tidak ada paket yang dipilih !',
                'warning'
            )
        }

    });

    $(document).on('click', 'input:checkbox[name=form-checkall-row-indikator]', function() {
        let rowChild = $('input:checkbox[name=form-check-row-indikator]').parents('tr').find('td')

        $('input:checkbox[name=form-check-row-indikator]').prop('checked', this.checked);
        let rowid = rowChild.find('.btnOutputKegiatan').attr('data-rowid');
        if (!this.checked) {


            if (typeof rowid === 'undefined') {
                rowChild.addClass('disabled')
                // rowChild.find('input').attr('readonly', 'readonly')
                rowChild.find('input').val('')
            } else {

                rowChild.addClass('disabled')
                // rowChild.find('input').attr('readonly', 'readonly')
                rowChild.find('select').attr('disabled', 'disabled')
                rowChild.find('button.btnOutputKegiatan').attr('disabled', 'true')
                rowChild.find('input').val('')
                rowChild.find('.totalbtnOutputKegiatan').html("0")

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
                rowChild.find('button.btnOutputKegiatan').removeAttr('disabled')
            }



        }
    });


    function removeSessionStorageContaining(value) {
        // Iterate through all keys in session storage
        for (let i = 0; i < sessionStorage.length; i++) {
            const key = sessionStorage.key(i);
            // Check if the key contains the specified value
            if (key.includes(value)) {
                sessionStorage.removeItem(key); // Remove matching item
                i--; // Decrement index because we've removed the current item
            }
        }
    }

    $(document).on('change', 'input:checkbox[name=form-check-row-indikator]', function() {
        let element_checkAll = $('input:checkbox[name=form-checkall-row-indikator]'),
            isAllChecked = false,
            element_parentsColumn = $(this).parents('tr').find('td');
        let rowid = element_parentsColumn.find('.btnOutputKegiatan').attr('data-rowid');

        if (!$(this).is(':checked')) {

            if (typeof rowid === 'undefined') {
                element_parentsColumn.addClass('disabled')
                // element_parentsColumn.find('input').attr('readonly', 'readonly')
                element_parentsColumn.find('input').val('')
            } else {
                element_parentsColumn.addClass('disabled')
                // element_parentsColumn.find('input').attr('readonly', 'readonly')
                element_parentsColumn.find('select').attr('disabled', 'disabled')
                element_parentsColumn.find('button.btnOutputKegiatan').attr('disabled', 'true')
                element_parentsColumn.find('input').val('')
                element_parentsColumn.find('.totalbtnOutputKegiatan').html("0")
            }




            removeSessionStorageContaining(rowid)

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
                element_parentsColumn.find('button.btnOutputKegiatan').removeAttr('disabled')

            }
        }

        if ($('input:checkbox[name=form-check-row-indikator]:checked').length == $('input:checkbox[name=form-check-row-indikator]').length) {
            isAllChecked = true
        }

        element_checkAll.prop('checked', isAllChecked)
    });
</script>


<script>
    $(document).on('click', '.save-btn-output-kegiatan', function(e) {
        e.preventDefault();
        indikatorId = $(this).attr("data-indikatorid");
        var selectedItems = [];
        var errorMessages = [];
        var totalJumlahTarget = 0;
        var totalJumlahOutcome1 = 0;
        var totalJumlahOutcome2 = 0;
        var totalJumlahOutcome3 = 0;
        var TargetlengthFix = 2;
        var Outcome1lengthFix = 2;
        var Outcome2lengthFix = 2;
        var Outcome3lengthFix = 2;
        var targetTotals = {};
        var outcome1Totals = {};
        var outcome2Totals = {};
        var outcome3Totals = {};

        $('.checkboxOgiat:checked').each(function() {

            selectedItems.push({
                length: $('.checkboxOgiat:checked').length
            });
        });

        if (errorMessages.length > 0) {
            // Menampilkan pesan kesalahan jika ada

            errorMessages.forEach(function(message) {
                Swal.fire('Peringatan', message, 'warning');
            });
        } else if (sessionStorage.length == 0) {

            Swal.fire(
                'Gagal Menyimpan',
                'Tidak ada Paket yang dipilih !',
                'warning'
            )

        } else if (selectedItems.length > 0) {

            $('.totalbtnOutputKegiatan[data-rowid="' + indikatorId + '"]').html(selectedItems.length);

            sessionStorage.setItem(indikatorId, JSON.stringify(selectedItems));
            $('#modalPilihOutputKegiatan').modal('hide');
        } else {
            Swal.fire(
                'Gagal Menyimpan',
                'Tidak ada output kegiatan yang dipilih !',
                'warning'
            )
        }
    });
</script>



<script>
    $(document).on('click', 'input:checkbox[name=form-checkall-row-output-kegiatan]', function() {
        let rowChild = $('input:checkbox[name=form-check-row-output-kegiatan]').parents('tr').find('td')

        $('input:checkbox[name=form-check-row-output-kegiatan]').prop('checked', this.checked);
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



    $(document).on('change', 'input:checkbox[name=form-check-row-output-kegiatan]', function() {
        let element_checkAll = $('input:checkbox[name=form-checkall-row-output-kegiatan]'),
            isAllChecked = false,
            element_parentsColumn = $(this).parents('tr').find('td');
        let rowid = element_parentsColumn.find('.paket').attr('data-rowid');
        let RowId = $(this).first().data('id');
        // let parentRowId = $('.ogiat-row').data('parent-rowid');


        let indikatorID = $('button.save-btn-output-kegiatan').data("indikatorid");


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



            // if (parentRowId == RowId) {
            $(`.ogiat-row[data-parent-rowid="${RowId}"]`).each(function() {
                $(this).find('td').addClass('disabled');
                $(this).find('td').find('input').attr('readonly', 'readonly');
                $(this).find('td').find('select').attr('disabled', 'disabled');
                $(this).find('td').find('button.paket').attr('disabled', 'disabled');
            });
            // }


            // sessionStorage.removeItem(indikatorID + "|" + rowid);
            sessionStorage.removeItem("oGIAT_" + rowid);
            sessionStorage.removeItem("Paket_" + rowid);
            // sessionStorage.removeItem(indikatorID);

            let dataID = $(".__buat-dokumen-pilih-template").data('id')
            $("tr[data-row-id]").each(function() {
                let elParent = $(this)
                // console.log(elParent.find('.__inputTemplateRow-target').data('targetsatuan'))
                let row_id = elParent.data('row-id')

                $.ajax({
                    url: "<?php echo site_url('renstra/get-rumus-outcome/') ?>" + dataID + "/" + row_id,
                    type: 'GET',
                    data: {},
                    success: (arr) => {
                        if (arr[row_id] !== undefined) {


                            if (elParent.find('.__inputTemplateRow-target').data('targetsatuan') !== undefined) {
                                let total = 0
                                arr[row_id][elParent.find('.__inputTemplateRow-target').data('targetsatuan')]['parent'].forEach((v) => {
                                    let satuan = arr[row_id][elParent.find('.__inputTemplateRow-target').data('targetsatuan')]['satuan']
                                    var cleanedSatuan = satuan.replace(/ /g, ''); // Menghapus spasi

                                    // Jika 'satuan' mengandung '%', bersihkan simbolnya
                                    if (satuan.includes('%')) {
                                        cleanedSatuan = cleanedSatuan.replace('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                                    }
                                    if (satuan.includes('M3/detik')) {
                                        cleanedSatuan = cleanedSatuan.replace(/\//g, "");

                                    }



                                    let getOgiat = sessionStorage.getItem(`oGIAT_${v}`)
                                    let getOgiatData = JSON.parse(getOgiat)
                                    if (getOgiatData !== null) {


                                        if ($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseInt($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }
                                        if ($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseInt($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }


                                        if ($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseInt($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }



                                        if ($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                            let nilai = parseInt($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                            if (!isNaN(nilai)) {
                                                total += nilai
                                            }
                                        }
                                    }
                                })
                                $(this).find('.__inputTemplateRow-target[data-row-id=' + row_id + ']').val(total)
                            }
                            if (elParent.find('.__inputTemplateRow-outcome').data('outcome1satuan') !== undefined) {
                                let total1 = 0
                                arr[row_id][elParent.find('.__inputTemplateRow-outcome').data('outcome1satuan')]['parent'].forEach((v) => {
                                    let satuan = arr[row_id][elParent.find('.__inputTemplateRow-outcome').data('outcome1satuan')]['satuan']
                                    var cleanedSatuan = satuan.replace(/ /g, ''); // Menghapus spasi

                                    // Jika 'satuan' mengandung '%', bersihkan simbolnya
                                    if (satuan.includes('%')) {
                                        cleanedSatuan = cleanedSatuan.replace('%', 'percent'); // Hapus simbol '%' dari 'satuan'
                                    }
                                    if (satuan.includes('M3/detik')) {
                                        cleanedSatuan = cleanedSatuan.replace(/\//g, "");

                                    }

                                    if ($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseInt($('.__targetValue-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }
                                    }
                                    if ($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseInt($('.__outcome1Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }
                                    }
                                    if ($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseInt($('.__outcome2Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }
                                    }
                                    if ($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val() !== undefined) {
                                        let nilai = parseInt($('.__outcome3Value-' + cleanedSatuan + '[data-row-id=' + v + ']').val().replaceAll(".", ""))
                                        if (!isNaN(nilai)) {
                                            total1 += nilai
                                        }
                                    }
                                })
                                $(this).find('.__inputTemplateRow-outcome[data-row-id=' + row_id + ']').val(total1)
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Gagal',
                            icon: "warning",
                            text: 'Rumus Tidak Ditemukan!',
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

            // sessionStorage.clear()
        } else {
            if (typeof rowid === 'undefined') {
                element_parentsColumn.removeClass('disabled')
                element_parentsColumn.find('input').removeAttr('readonly')


                $(`.ogiat-row[data-parent-rowid="${RowId}"]`).each(function() {
                    $(this).find('td').removeClass('disabled');
                    $(this).find('td').find('input').removeAttr('readonly');
                    $(this).find('td').find('select').removeAttr('disabled');
                    $(this).find('td').find('button.paket').removeAttr('disabled');
                });
            } else {
                element_parentsColumn.removeClass('disabled')

                if (element_parentsColumn.find('input[data-pktype]').attr('data-pktype') == "balai") {
                    element_parentsColumn.find('input').removeAttr('readonly')
                }
                element_parentsColumn.find('select').removeAttr('disabled')
                element_parentsColumn.find('button.paket').removeAttr('disabled')


                $(`.ogiat-row[data-parent-rowid="${RowId}"]`).each(function() {
                    $(this).find('td').removeClass('disabled');
                    $(this).find('td').find('input').removeAttr('readonly');
                    $(this).find('td').find('select').removeAttr('disabled');
                    $(this).find('td').find('button.paket').removeAttr('disabled');
                });


            }
        }




        if ($('input:checkbox[name=form-check-row-output-kegiatan]:checked').length == $('input:checkbox[name=form-check-row-output-kegiatan]').length) {
            isAllChecked = true
        }

        element_checkAll.prop('checked', isAllChecked)
    });
</script>