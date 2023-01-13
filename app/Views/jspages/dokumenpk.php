<script src="https://unpkg.com/imask"></script>
<script>
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
        element_btnSaveEditDokumen = $('.__save-update-dokumen')

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

        $('#modal-preview-cetak').on('shown.bs.modal', function() {
            $(document).off('focusin.modal');
        });

    })



    $(document).on('change', 'select[name=filter-satker]', function() {
        window.location.href = "<?php echo base_url('dokumenpk-balai-satker') ?>/" + $(this).val()
    })



    $(document).on('click', '.__opsi-template', function() {
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

        if (!this.checked) {
            rowChild.addClass('disabled')
        } else {
            rowChild.removeClass('disabled')
        }
    });



    $(document).on('change', 'input:checkbox[name=form-check-row]', function() {
        let element_checkAll = $('input:checkbox[name=form-checkall-row]'),
            isAllChecked = false,
            element_parentsColumn = $(this).parents('tr').find('td')

        if (!$(this).is(':checked')) {
            element_parentsColumn.addClass('disabled')
            element_parentsColumn.find('input').attr('readonly', 'readonly')
            element_parentsColumn.find('input').val('')
        } else {
            element_parentsColumn.removeClass('disabled')
            element_parentsColumn.find('input').removeAttr('readonly')
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
                                    <p>Pembuatan dokumen perjanjian kinerja dapat di buat jika satker-satker sudah menginputkan dokumen perjanjian kinerja. Daftar satker dapat dilihat pada bagian bawah form</p>
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
        if (saveDokumenValidation()) {
            let oldButtonText = element_btnSaveDokumen.text()
            element_btnSaveDokumen.attr('disabled', 'disabled')
            element_btnSaveDokumen.text('Menyiimpan Dokumen')

            setTimeout(function() {
                $('input[name=total-anggaran]').prop("disabled", false)
                let formData = getFormValue();


                if ($(this).attr('data-dokumen-id')) {
                    formData['revision_dokumen_id'] = $(this).data('dokumen-id')
                    formData['revision_dokumen_master_id'] = $(this).data('dokumen-master-id')
                }

                $.ajax({
                    url: "<?php echo site_url('dokumenpk/create') ?>",
                    type: 'POST',
                    data: formData,
                    success: (res) => {
                        if (res.status) {
                            location.reload()
                        }
                        else {
                            alert('error, terjadi kesalahan pengiriman data, bisa jadi di karenakan jaringan internet anda yang kurang baik')
                            location.reload()
                        }
                    },
                    fail: (xhr) => {
                        alert('Terjadi kesalahan pada sistem')
                        console.log(xhr)
                        location.reload()
                    }
                })
            }, 2000)
        }
    })



    element_btnSaveEditDokumen.on('click', function() {
        if (saveDokumenValidation()) {
            let oldButtonText = element_btnSaveEditDokumen.text()
            element_btnSaveEditDokumen.attr('disabled', 'disabled')
            element_btnSaveEditDokumen.text('Menyiimpan Dokumen')

            setTimeout(function() {
                let formData = getFormValue(),
                dataId = $(this).data('id')

                formData['id'] = $(this).data('id')

                $('input[name=total-anggaran]').prop("disabled", false)

                $.ajax({
                    url: "<?php echo site_url('dokumenpk/editDokumen') ?>",
                    type: 'POST',
                    data: formData,
                    success: (res) => {
                        if (res.status) {
                            location.reload()
                        }
                        else {
                            alert('error, terjadi kesalahan pengiriman data, bisa jadi di karenakan jaringan internet anda yang kurang baik')
                            location.reload()
                        }
                    },
                    fail: (xhr) => {
                        alert('Terjadi kesalahan pada sistem')
                        console.log(xhr)
                        location.reload()
                    }
                })
            }, 2000)
        }
    })



    $(document).on('click', '.__prepare-revisi-dokumen', function() {

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

        // let dataID = $(this).data('id'),
        //     templateID = $(this).data('template-id')

        // const promiseGetTemplate = new Promise((resolve, reject) => {
        //     $.ajax({
        //         url: "<?php echo site_url('dokumenpk/get-template/') ?>" + templateID,
        //         type: 'GET',
        //         success: (res) => {
        //             preapreForm_afterChooseTemplate({
        //                 templateId: templateID,
        //                 data      : res,
        //                 target    : 'koreksi'
        //             })

        //             element_modalFormTitle.html(`
        //                 <h6>Koreksi Dokumen</h6>
        //                 <small>${ res.template.title }</small>
        //             `)

        //             resolve(dataID)
        //         },
        //         fail: (xhr) => {
        //             alert("Terjadi kesalahan pada sistem")
        //             console.log(xhr)
        //             reject(then)
        //         }
        //     })
        // })

        // promiseGetTemplate.then((res) => {
        //     $.ajax({
        //         url: "<?php echo site_url('dokumenpk/detail/') ?>" + res,
        //         type: 'GET',
        //         success: (res) => {
        //             res.rows.forEach((data, key) => {
        //                 let elementInput_target = $('.__inputTemplateRow-target[data-row-id=' + data.template_row_id + ']'),
        //                     elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id=' + data.template_row_id + ']')

        //                 elementInput_target.val(data.target_value)
        //                 elementInput_outcome.val(data.outcome_value)

        //                 if (data.is_checked == '0') elementInput_target.parents('tr').find('input:checkbox[name=form-check-row]').trigger('click')
        //             })

        //             res.kegiatan.forEach((data, key) => {
        //                 let elementInput_target = $('tr[data-kegiatan-id='+data.id+']').find('input[name=kegiatan-anggaran]')
        //                 elementInput_target.val(data.anggaran)
        //             })

        //             $('input[name=total-anggaran]').val(res.dokumen.total_anggaran)
        //             $('input[name=ttd-pihak1]').val(res.dokumen.pihak1_ttd)
        //             $('input[name=ttd-pihak2]').val(res.dokumen.pihak2_ttd)

        //             $('.title-ttd-pihak1').text('KEPALA ' + res.dokumen.pihak1_initial)
        //             $('.title-ttd-pihak2').text('KEPALA ' + res.dokumen.pihak2_initial)

        //             if ($('input[name=ttd-pihak2-jabatan]').length) {
        //                 $('input[name=ttd-pihak2-jabatan]').val(res.dokumen.pihak2_initial)
        //             }

        //             if (res.dokumen.pihak1_is_plt == '1') $('input:checkbox[name=ttd-pihak1-plt]').prop('checked', true)
        //             if (res.dokumen.pihak2_is_plt == '1') $('input:checkbox[name=ttd-pihak2-plt]').prop('checked', true)

        //             $('select[name=created-kota]').val(res.dokumen.kota).trigger('change')
        //             $('select[name=created-bulan]').val(res.dokumen.bulan).trigger('change')
        //             $('select[name=created-tahun]').val(res.dokumen.tahun).trigger('change')

        //             if (res.dokumen.revision_message != null) {
        //                 $('.container-revision-alert').html(`
        //                     <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
        //                         <h5 class="alert-heading">Perlu Di Koreksi !</h5>
        //                         <p>${res.dokumen.revision_message}</p>
        //                     </div>
        //                 `)
        //             }


        //             render_prepare_btnSubmitToRevision({
        //                 dokumenID: res.dokumen.id,
        //                 dokumenMasterID: res.dokumen.revision_master_dokumen_id ?? res.dokumen.id
        //             });
        //             $('#modalForm').modal('show')
        //         },
        //         fail: (xhr) => {
        //             alert("Terjadi kesalahan pada sistem")
        //             console.log(xhr)
        //         }
        //     })
        // })
    })



    $(document).on('click', '.__lihat-dokumen', function() {
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
                url: "<?php echo site_url('dokumenpk/get-template/') ?>" + templateId,
                type: 'GET',
                success: (res) => {
                    preapreForm_afterChooseTemplate({
                        templateId: templateId,
                        data: res,
                        target: 'koreksi'
                    })

                    element_modalFormTitle.html(`
                        <h6>Lihat Dokumen</h6>
                        <small>${ res.template.title }</small>
                    `)

                    resolve(dataId)
                },
                fail: (xhr) => {
                    alert("Terjadi kesalahan pada sistem")
                    console.log(xhr)
                    reject(then)
                }
            })
        })

        promiseGetTemplate.then((res) => {
            $.ajax({
                url: "<?php echo site_url('dokumenpk/detail/') ?>" + res,
                type: 'GET',
                success: (res) => {
                    res.rows.forEach((data, key) => {
                        let elementInput_target = $('.__inputTemplateRow-target[data-row-id=' + data.template_row_id + ']'),
                            elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id=' + data.template_row_id + ']')

                        // elementInput_target.val(data.target_value)
                        // elementInput_outcome.val(data.outcome_value)
                        elementInput_target.val(formatRupiah(data.target_value.toString().replaceAll('.', ',')))
                        elementInput_outcome.val(formatRupiah(data.outcome_value.toString().replaceAll('.', ',')))

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
                    $('select[name=created-tahun]').val(res.dokumen.tahun).trigger('change')

                    if (res.dokumen.revision_message != null) {
                        $('.container-revision-alert').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Perlu Di Koreksi !</h5>
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
                                    </tr>
                                `
                            }
                        });

                        $('.container-list-revision-message').find('tbody').html(listRevisionMessage);
                    }

                    params.beforeModalMount(res)

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
                res.rows.forEach((data, key) => {
                    let elementInput_target = $('.__inputTemplateRow-target[data-row-id=' + data.template_row_id + ']'),
                        elementInput_outcome = $('.__inputTemplateRow-outcome[data-row-id=' + data.template_row_id + ']')

                    elementInput_target.val(formatRupiah(data.target_value.toString().replaceAll('.', ',')))
                    elementInput_outcome.val(formatRupiah(data.outcome_value.toString().replaceAll('.', ',')))

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
                $('select[name=created-tahun]').val(res.dokumen.tahun).trigger('change')
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
                    let renderCheck = ''

                    if (data.iscreatedPK > 0) {
                        renderCheck = '<i class="fas fa-check"></i>'
                    } else if (data.iscreatedPKBeforeAcc > 0) {
                        renderCheck = '<div class="d-flex justify-content-between align-items-center"><span class = "badge badge-pill px-3 font-weight-bold ' + data.status_color + '"> ' + data.status_now + ' </span> <div > ';
                    }
                    renderList += `
                        <li class="list-group-item d-flex justify-content-between">
                            <label>${data.satker}</label>
                            ${renderCheck}
                        </li>
                    `
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
            kegiatan = []

        if (element_rowItem_anggaran_kegiatan != undefined) {

            if (element_rowItem_anggaran_kegiatan == 0 || element_rowItem_anggaran_kegiatan == null) {

                Swal.fire(
                    'Peringatan',
                    'Nama dan Anggaran Kegiatan belum terisi',
                    'warning'
                )
                return false
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
                url: "<?php echo site_url('dokumenpk/get-tgiat-for-formpk?exists=') ?>" + JSON.stringify(kegiatan),
                dataType: 'json'
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
            kegiatan = []

        $('.__inputTemplateRow-target').each((key, element) => {
            let elementInput_target = $(element),
                elementInput_outcome = $('.__inputTemplateRow-outcome').eq(key),
                element_checkRow = $('input:checkbox[name=form-check-row]').eq(key)

            rows.push({
                id: elementInput_target.data('row-id'),
                target: elementInput_target.val().replace('.', ''),
                outcome: elementInput_outcome.val().replace('.', ''),
                isChecked: element_checkRow.is(':checked') ? '1' : '0'
            })
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
            kegiatan: kegiatan,
            totalAnggaran: $('input[name=total-anggaran]').val(),
            ttdPihak1: $('input[name=ttd-pihak1]').val(),
            ttdPihak1_isPlt: $('input:checkbox[name=ttd-pihak1-plt]').is(':checked') ? '1' : '0',
            ttdPihak2: $('input[name=ttd-pihak2]').val(),
            ttdPihak2_isPlt: $('input:checkbox[name=ttd-pihak2-plt]').is(':checked') ? '1' : '0',
            kota: $('select[name=created-kota]').val(),
            kotaNama: $('input[name=created-kota-nama]').val(),
            bulan: $('select[name=created-bulan]').val(),
            tahun: $('select[name=created-tahun]').val()
        }
        if ($('input[name=ttd-pihak2-jabatan]').length) inputValue.ttdPihak2Jabatan = $('input[name=ttd-pihak2-jabatan]').val()

        return inputValue
    }



    function saveDokumenValidation() {
        let checkInputKegiatanAnggatan = true,
            checkInputKegiatanManual = true,
            checkInputTarget = true,
            checkInputOutcome = true

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
    }



    function cetakDokumen(_dokumenID, _toConfirm) {
        $.ajax({
            url: "<?php echo site_url('dokumenpk/export-pdf/') ?>" + _dokumenID,
            type: 'GET',
            success: (res) => {
                $('#modal-cetak-dokumen-revisioned').modal('hide')

                setTimeout(() => {
                    let element_iframePreviewDokumen = element_modalPreviewCetakDokumen.find('iframe')

                    if (res.dokumen.revision_message != null) {
                        element_iframePreviewDokumen.css({
                            'height': '60vh'
                        })
                        $('.container-revision-alert-cetak').html(`
                            <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                                <h5 class="alert-heading">Perlu Di Koreksi !</h5>
                                <p>${res.dokumen.revision_message}</p>
                            </div>
                        `)
                    } else {
                        element_iframePreviewDokumen.css({
                            'height': '80vh'
                        })
                        $('.container-revision-alert-cetak').html('')
                    }

                    element_iframePreviewDokumen.attr('src', '<?php echo site_url('dokumen-perjanjian-kinerja.pdf') ?>')
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
            }
        })
    }



    function preapreForm_afterChooseTemplate(params = {
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

        renderFormTemplate(params.data, params.target)
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
                let renderCheck = ''

                if (data.iscreatedPK > 0) renderCheck = '<i class="fas fa-check mt-2"></i>'

                renderCheckListSatkerBalai += `
                    <li class="list-group-item d-flex justify-content-between">
                        <label>${ data.satker }</label>
                        ${renderCheck}
                    </li>
                `
            });


            if (params.data.template.type == 'master-balai' || params.data.template.type == 'balai') {
                $('.container-revision-alert').append(`
                    <div class="bg-danger text-white pt-3 pr-3 pb-1 pl-3" role="alert">
                        <h5 class="alert-heading">Informasi</h5>
                        <p>Pembuatan dokumen perjanjian kinerja dapat di buat jika satker-satker sudah menginputkan dokumen perjanjian kinerja. Daftar satker dapat dilihat pada bagian bawah form</p>
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



    function renderFormTemplate(_data, _target) {
        let template = _data.template,
            templateExtraData = _data.templateExtraData,
            render_rowsForm = renderFormTemplate_rowTable(_data.templateRow, _data.template.type),
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
            theadBalaiTargetNumber = ''

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



        if (
            _data.template.type == 'eselon1' ||
            _data.template.type == 'eselon2' ||
            _data.template.type == 'master-balai'
        ) {
            classDNoneOutcome = 'd-none'
        }

        if (_data.template.type == 'master-balai' || _data.template.type == 'eselon1') {
            titleTheadTable = '';
            switch (_data.template.type) {
                case 'master-balai':
                    titleTheadTable = 'Target Dari Satker';
                    break;

                case 'eselon1':
                    titleTheadTable = 'Acuan';
                    break;

                default:
                    titleTheadTable = '';
                    break;
            }

            theadBalaiTarget = '<td class="text-center" style="width: 250px">Target ' + <?php echo $sessionYear ?> + '</td>';
            theadBalaiTargetNumber = '<td class="text-center p-2">(3)</td>';
        } else {
            titleTheadTable = 'Target ' + <?php echo $sessionYear ?>
        }

        let render = `
            <input type="hidden" name="revision_same_year" value="${inputValue_revisionSameYear}" />

            <div class="container-revision-alert">
                ${render_warningDokumenYearRevisoin}
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="text-center" colspan="3">Sasaran Program / Sasaran Kegiatan / Indikator</td>
                        <td class="text-center" style="width: 250px">
                            ${titleTheadTable}
                        </td>
                        ${theadBalaiTarget}
                        <td class="text-center ${classDNoneOutcome}" style="width: 250px">
                            Outcome
                        </td>
                    </tr>
                    <tr style="font-size:10px">
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
                                        <button class="btn btn-sm btn-primary" id="__add-item-kegiatan">
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
                                    <td class="align-middle" colspan="2"> 
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
                                <select class="form-control" name="created-bulan">
                                    ${render_opsiBulan}
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
                        <input class="form-control" name="ttd-pihak1" placeholder="Masukkan Nama Penanda Tangan" required  onkeyup="this.value = this.value.toUpperCase();" onkeypress="return inputHarusHuruf(event)"  />
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
                    <h5 style="color: #000">Daftar Koreksi</h5>
                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <th width="300px">Tanggal</th>
                            <th>Pesan Koreksi</th>
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



    function renderFormTemplate_rowTable(_data, _templateType) {
        let rows = '',
            rowNumber = 1,
            colspanSectionTitle = 3,
            classDNoneOutcome = ''

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
                    let renderInputTarget = ''
                    if (_templateType == 'master-balai' || _templateType == 'eselon1') {
                        renderInputTarget = `
                            <td>
                                <div class="input-group mr-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="width: 80px">${ data.targetDefualtValue }</span>
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
                                    >
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
                                    value="${ data.targetDefualtValue }"
                                    data-row-id="${ data.id }"
                                    onkeyup="return this.value = formatRupiah(this.value, '')"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text">${ data.target_satuan }</span>
                                </div>
                            </div>
                        </td>
                        `
                    }

                    rows += `
                        <tr>
                            <td class="text-center align-middle" width="50px">
                                <input type="checkbox" name="form-check-row" checked />
                            </td>
                            <td class="align-middle" width="50px">${ rowNumber++ }</td>
                            <td class="align-middle">${ data.title }</td>
                            ${renderInputTarget}
                            <td class="${classDNoneOutcome}">
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        class="form-control __inputTemplateRow-outcome" 
                                        placeholder="Masukkan Nilai"
                                        value="${ data.outcomeDefaultValue }"
                                        data-row-id="${ data.id }"
                                        onkeyup="return this.value = formatRupiah(this.value, '')"
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
            <input class="form-control" name="ttd-pihak2" placeholder="Masukkan Nama Penanda Tangan" onkeyup="this.value = this.value.toUpperCase();" onkeypress="return inputHarusHuruf(event)" />
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



    function renderFormTemplate_opsiTahun(_data) {
        let renderOptions = ''

        for (let iTahun = (parseInt(_data)); iTahun <= (parseInt(_data)); iTahun++) {
            let isSelected = iTahun == date.getFullYear() ? 'selected' : ''

            renderOptions += `<option ${isSelected}>${iTahun}</option>`
        }

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