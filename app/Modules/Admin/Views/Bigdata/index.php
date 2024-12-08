<?= $this->extend('admin/layouts/default') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main w-100">
            <div class="d-flex justify-content-between w-100">
                <h5 class="kt-subheader__title">
                    BIG DATA
                </h5>
                <?= csrf_field() ?>

                <div>
                    <button class="btn btn-default" data-toggle="modal" data-target="#modalFilterData">
                        <i class="fas fa-filter"></i> Filter Data
                    </button>
                    <button class="btn btn-default" data-toggle="modal" data-target="#modalFilterKolom">
                        <i class="fas fa-filter"></i> Kolom Tabel
                    </button>
                    <button class="btn btn-success" name="prepare-download-bigdata">
                        <i class="fas fa-file-excel"></i> Download Excel
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
        <div class="kt-portlet__body p-0">
            <div class="kt-section p-0 mb-0">
                <div class="row p-0">
                    <div class="col-md-12" style="display:inline-block; overflow-x:auto; height: 63vh;">
                        <table class="table table-bordered mb-0 table-striped _table-data" id="_table-data" style="width: <?php echo $tableWidth ?>px; position: relative;">
                            <thead>
                                <tr class=" text-center bg-purple">
                                    <?php foreach ($column as $keyColumn => $dataColumn) : ?>
                                        <th class="bg-purple <?php if ($dataColumn['value'] != 'no') echo 'd-none' ?> <?php echo '_cell_' . $dataColumn['value'] ?>" width="<?php echo $dataColumn['widthColumn'] . 'px' ?>" style="position: sticky; top: 0;">
                                            <?php echo $dataColumn['label'] ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody style="font-size: 12px">
                            </tbody>
                        </table>
                        <div class="_warning-message-data-not-found text-center pt-3 d-none">
                            <h3>Data Tidak Di Temukan</h3>
                            <small>Periksa kembali filter data yang anda atur</small>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="__btn-load-more-data btn btn-sm btn-primary m-3">
                                <i class="fas fa-sync"></i> Muat Data Lainnya
                            </button>
                        </div>
                    </div>

                    <div class="col-md-12 d-flex justify-content-end" style="background-color: #F1EEE9">
                        <div class="p-2">
                            Menampilkan <strong class="_showed-data">-</strong> / <strong class="_total-data">-</strong> Data
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<table class="d-none" id="_exported_table"></table>
<!-- end:: Content -->


<!-- Modal Filter Data -->
<div class="modal fade" id="modalFilterData" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Filter Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="Tahun">Tahun</label>
                    <select class="form-control select2" name="filter-tahun">
                        <option value="0" selected>Semua Data</option>
                        <?php for ($i = 2013; $i <= date('Y') + 1; $i++) {
                            echo '  <option value="' . $i . '" selected>' . $i . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Opsi Data</label>
                    <select class="form-control select2" name="filter-opsi-data">
                        <option value="0" selected>Semua Data</option>
                        <!-- <option value="1">Hanya Data Aktif</option> -->
                        <option value="2">Hanya Data Di Blokir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Satker</label>
                    <select class="form-control select2" name="filter-satker">
                        <option value="*">Semua</option>
                        <?php foreach ($data['satker'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Program</label>
                    <select class="form-control select2" name="filter-program">
                        <option value="*">Semua</option>
                        <?php foreach ($data['program'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Kegiatan</label>
                    <select class="form-control select2" name="filter-kegiatan">
                        <option value="*">Pilih Program Lebih Dahulu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Output</label>
                    <select class="form-control select2" name="filter-output">
                        <option value="*">Pilih Kegiatan Lebih Dahulu</option>
                        <?php foreach ($data['output'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Suboutput</label>
                    <select class="form-control select2" name="filter-suboutput">
                        <option value="*">Pilih Output Lebih Dahulu</option>
                        <?php foreach ($data['suboutput'] as $key => $value) : ?>
                            <option value="<?php echo $value->id ?>"><?php echo $value->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Pagu Total</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Rp.</span>
                        </div>
                        <input type="text" id="filter-pagutotal-start" class="form-control" name="filter-pagutotal-start" placeholder="Min Pagu Total">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Hingga</span>
                        </div>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="">Rp.</span>
                        </div>
                        <input type="text" id="filter-pagutotal-end" class="form-control" name="filter-pagutotal-end" placeholder="Max Pagu Total">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="act-filter-data" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Filter Column -->


<!-- Modal Filter Column -->
<div class="modal fade" id="modalFilterKolom" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Kolom Tabel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-check mb-3">
                    <input name="filter-kolom-all" class="form-check-input" type="checkbox" value="*" id="filter-all-column" checked>
                    <label class="form-check-label" for="filter-all-column">
                        <strong>Semua Kolom</strong>
                    </label>
                </div>
                <?php foreach ($column as $keyColumnFilter => $dataColumnFilter) : ?>
                    <?php if ($dataColumnFilter['value'] != 'no') : ?>
                        <div class="form-check mt-2">
                            <input name="filter-kolom" class="form-check-input" type="checkbox" value="<?php echo $dataColumnFilter['value'] ?>" data-label="<?php echo $dataColumnFilter['label'] ?>" id="<?php echo 'filter_' . $dataColumnFilter['value'] ?>" <?php if (in_array($dataColumnFilter['value'], $defaultColumn))  echo 'checked' ?>>
                            <label class="form-check-label" for="<?php echo 'filter_' . $dataColumnFilter['value'] ?>">
                                <?php echo $dataColumnFilter['label'] ?>
                            </label>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" name="act-filter" class="btn btn-primary">Terapkan</button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal Filter Column -->


<!-- Modal export data -->
<div class="modal fade" id="modalDownloadData" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Download Big Data</h5>
            </div>
            <div class="modal-body p-0">
                <ul class="list-group rounded-0 _list-downloaded">
                    <li class="text-center p-3">
                        <i class="fas fa-sync fa-spin"></i> Memuat File
                    </li>
                </ul>
            </div>
            <div class="modal-footer d-none">
                <button class="btn btn-success" data-dismiss="modal">
                    <i class="fas fa-check"></i> Selesai
                </button>
            </div>
        </div>
    </div>
</div>
<!-- end-of: Modal export data -->
<?= $this->endSection() ?>





<?= $this->section('footer_js') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php echo script_tag('js/imask.js'); ?>


<script>
    var page = 0,
        showedData = 0,
        rowNumber = 1,
        element_buttonLoadMore = $(".__btn-load-more-data"),
        element_iconLoadMore = element_buttonLoadMore.find('i.fas'),
        element_tableWarningDataNotFound = $("._warning-message-data-not-found")


    var filterPagutotalStartMask = IMask(
        document.getElementById('filter-pagutotal-start'), {
            mask: Number,
            thousandsSeparator: '.'
        }
    );

    var filterPagutotalEndMask = IMask(
        document.getElementById('filter-pagutotal-end'), {
            mask: Number,
            thousandsSeparator: '.'
        }
    );


    $(document).ready(function() {
        $('button[name=act-filter]').trigger('click')
        getData()

        $('.select2').select2()
    })



    $(document).on('change', 'select[name=filter-program]', function() {
        getFilterData_selectLookUp({
            parentInitial: 'Program',
            data: {
                parentValue: $(this).val()
            },
            childInitial: 'Kegiatan',
            childTarget: 'kegiatan',
            childElement: 'select[name=filter-kegiatan]'
        })
    })



    $(document).on('change', 'select[name=filter-kegiatan]', function() {
        getFilterData_selectLookUp({
            parentInitial: 'Kegiatan',
            data: {
                parentValue: $(this).val()
            },
            childInitial: 'Output',
            childTarget: 'output',
            childElement: 'select[name=filter-output]'
        })
    })



    $(document).on('change', 'select[name=filter-output]', function() {
        getFilterData_selectLookUp({
            parentInitial: 'Output',
            data: {
                parentValue: $(this).val(),
                kdgiat: $('select[name=filter-kegiatan]').val()
            },
            childInitial: 'Sub Output',
            childTarget: 'suboutput',
            childElement: 'select[name=filter-suboutput]'
        })
    })



    $('input:checkbox[name=filter-kolom-all]').change(function() {
        let checked = true
        if (!this.checked) checked = false
        $('input:checkbox[name=filter-kolom]').prop('checked', checked)
    })



    $('input:checkbox[name=filter-kolom]').change(function() {
        let unchecked_checkboxFilterKolom = $('input:checkbox[name=filter-kolom]:not(:checked)')

        let checkedAll = true
        if (unchecked_checkboxFilterKolom.length > 0) checkedAll = false

        $('input:checkbox[name=filter-kolom-all]').prop('checked', checkedAll)
    })



    $('button[name=act-filter]').click(function() {
        let tableWidth = 0

        $('input:checkbox[name=filter-kolom]').each((key, element) => {
            let cellElement = $('._cell_' + element.value)
            let headerCellElement = $('th._cell_' + element.value)

            if ($(element).is(':checked')) {
                cellElement.removeClass('d-none')
                tableWidth += parseInt(headerCellElement.attr('width').replace('px', ''))
            } else {
                cellElement.addClass('d-none')
            }
        })

        $('._table-data').css({
            'width': tableWidth + 'px'
        })
        $('#modalFilterKolom').modal('hide')
    })



    $(document).on('click', 'button[name=act-filter-data]', function() {
        renderEmptyTable()
        page = 0
        rowNumber = 1
        getData({
            onBeforeSend: () => {
                $('#modalFilterData').modal('hide')
            }
        })
    })



    $('button[name=prepare-download-bigdata]').click(function() {
        prepareDownloadBigData()

        $('#modalDownloadData').modal({
            backdrop: 'static',
            keyboard: false
        })
    })


    element_buttonLoadMore.on('click', () => {
        getData()
    })



    function getData(params = {
        onBeforeSend: () => {},
        onSuccess: () => {}
    }) {
        element_tableWarningDataNotFound.addClass('d-none')
        element_buttonLoadMore.removeClass('d-none')

        $.ajax({
            url: "<?php echo site_url('bigdata/load-data') ?>",
            type: 'GET',
            data: {
                page: page,
                filter: getFilterDataValue()
            },
            // timeout: 2000,
            beforeSend: () => {
                element_iconLoadMore.addClass('fa-spin')
                if (params.hasOwnProperty('onBeforeSend')) params.onBeforeSend()
            },
            success: (res) => {
                if (res.data.length > 0) {
                    renderTableRow(res)
                    if (res.hasOwnProperty('totalData')) $('._total-data').text(res.totalData.total)

                    showedData += res.data.length
                    $('._showed-data').text(showedData)

                    element_iconLoadMore.removeClass('fa-spin')
                    page++

                    if (params.hasOwnProperty('onSuccess')) params.onSuccess()

                    $('button[name=act-filter]').trigger('click')

                    if ($('._total-data').text() == $('._showed-data').text()) element_buttonLoadMore.addClass('d-none')
                } else {
                    element_tableWarningDataNotFound.removeClass('d-none')
                    element_buttonLoadMore.addClass('d-none')
                }
            },
            fail: (xhr) => {
                alert("Terjadi Kesalahan Pada Sistem");
            }
        })
    }



    function getFilterDataValue() {
        let value = {}

        value.tahun = $('select[name=filter-tahun]').val()
        value.opsiData = $('select[name=filter-opsi-data]').val()
        if ($('select[name=filter-satker]').val() != '*') value.kdsatker = $('select[name=filter-satker]').val()
        if ($('select[name=filter-program]').val() != '*') value.kdprogram = $('select[name=filter-program').val()
        if ($('select[name=filter-kegiatan]').val() != '*') value.kdgiat = $('select[name=filter-kegiatan]').val()
        if ($('select[name=filter-output]').val() != '*') value.kdoutput = $('select[name=filter-output]').val()
        if ($('select[name=filter-suboutput]').val() != '*') value.kdsoutput = $('select[name=filter-suboutput]').val()
        if ($('input[name=filter-pagutotal-start]').val() != '') value.pagutotalStart = filterPagutotalStartMask.unmaskedValue
        if ($('input[name=filter-pagutotal-end]').val() != '') value.pagutotalEnd = filterPagutotalEndMask.unmaskedValue

        return value
    }



    function getFilterData_selectLookUp(params = {
        parentInitial: '',
        parentValue: '',
        childInitial: '',
        childTarget: '',
        childElement: ''
    }) {
        let sendData = params.data

        sendData.childTarget = params.childTarget

        $.ajax({
            type: 'GET',
            url: "<?php echo site_url('bigdata/filter-select-lookup') ?>",
            data: sendData,
            success: (res) => {
                $(params.childElement).empty();

                if (sendData.parentValue != '*') {
                    res.forEach((data, key) => {
                        let newOption = new Option(data.nama, data.id, true, true);
                        $(params.childElement).append(newOption);
                    });

                    let newOption
                    if (res.length > 0) {
                        newOption = new Option('Semua', '*', true, true)
                    } else {
                        newOption = new Option(params.childInitial + ' tidak di temukan', '*', true, true)
                    }

                    $(params.childElement).prepend(newOption).trigger('change')
                } else {
                    newOption = new Option('Pilih ' + params.parentInitial + ' Lebih Dahulu', '*', true, true)
                    $(params.childElement).prepend(newOption).trigger('change')
                }
            }
        })
    }



    function renderTableRow(_res) {
        let tableRow = ''

        _res.data.forEach((dataRow, keyRow) => {
            let rowColumn = ''

            _res.column.forEach((dataCol, keyCol) => {
                let text = dataRow[dataCol.value],
                    colAlign = dataCol.align ?? 'left'

                if (dataCol.hasOwnProperty('isNumberFormat')) {
                    if (dataCol.isNumberFormat) text = text ? formatRupiah(text, '') : 0
                }

                if (dataCol.value == 'no') text = rowNumber++

                rowColumn += `
                    <td class="text-${colAlign} _cell_${dataCol.value}">
                        ${text ?? ''}
                    </td>
                `;
            });

            tableRow += `<tr>${rowColumn}</tr>`
        });

        $('#_table-data tbody').append(tableRow)
    }



    function renderEmptyTable() {
        page = 0
        showedData = 0

        $('._total-data').text('0')
        $('._showed-data').text('0')
        $('#_table-data tbody').html('')
    }



    function prepareDownloadBigData() {
        $('#modalDownloadData .modal-footer').addClass('d-none')

        setTempColumn().then((res) => {
            $.ajax({
                url: "<?php echo site_url('bigdata/download/prepare') ?>",
                data: {
                    filter: getFilterDataValue()
                },
                type: 'get',
                success: (res) => {
                    let listDownload = ''
                    for (let _index = 1; _index <= res.totalFile; _index++) {
                        let listClassName = '_item-list-download-' + _index
                        listDownload += `
                            <li class="list-group-item d-flex justify-content-between ${listClassName}">
                                <div>
                                    <!-- <h6>Part ${_index}</h6> -->
                                    <!-- <small>monika-bigdata-part-${_index}.xlsx</small> -->
                                    <h6>Download Big Data</h6>
                                </div>
                                <div class="d-flex align-items-center _icon-status">
                                </div>
                            </li>
                        `
                    }
                    $('._list-downloaded').html(listDownload)

                    downloadBigData({
                        fileNumber: 1,
                        totalFile: res.totalFile
                    })
                }
            })
        });
    }



    function downloadBigData(params = {
        fileNumber: 0,
        totalFile: 0
    }) {
        var element_list = $('._item-list-download-' + params.fileNumber),
            element_iconStatus = element_list.find('._icon-status')

        $.ajax({
            type: 'GET',
            url: "<?php echo site_url('bigdata/download') ?>" + '?fileNumber=' + params.fileNumber,
            data: {
                filter: getFilterDataValue()
            },
            dataType: 'json',
            beforeSend: () => {
                element_list.addClass('active')
                element_iconStatus.html('<i class="fas fa-sync fa-spin"></i>')
            }
        }).done(function(data) {
            var $a = $("<a>");
            $a.attr("href", data.file);
            $("body").append($a);
            $a.attr("download", "monika-bigdata-part-" + params.fileNumber + ".xls");
            $a[0].click();
            $a.remove();

            element_list.removeClass('active')
            element_iconStatus.html('<i class="fas fa-check"></i>')

            if (params.fileNumber < params.totalFile) {
                downloadBigData({
                    fileNumber: params.fileNumber + 1,
                    totalFile: params.totalFile
                })
            }

            if (params.fileNumber == params.totalFile) {
                $('#modalDownloadData .modal-footer').removeClass('d-none')
            }
        });
    }



    function setTempColumn() {
        return new Promise((resolve, reject) => {
            let column = []
            $('input:checkbox[name=filter-kolom]').each((key, element) => {
                if ($(element).is(':checked')) {
                    column.push({
                        label: $(element).data('label'),
                        value: element.value
                    });
                }
            })
            let columnString = JSON.stringify(column)

            $.ajax({
                url: "<?php echo site_url('bigdata/download/set-temp-column') ?>",
                type: "POST",
                data: {
                    csrf_test_name: $('input[name=csrf_test_name]').val(),
                    columnString: columnString
                },
                success: (res) => {
                    resolve(res)
                },
                fail: () => {
                    alert("terjadi kesalahan pada sistem")
                    reject();
                }
            })
        })
    }



    function download(fileUrl) {
        var a = document.createElement("a");
        a.href = fileUrl;
        a.click();
    }



    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var exportedTable = document.getElementById('_exported_table')

        exportedTable.innerHTML = tableSelect.innerHTML
        $("#_exported_table .d-none").remove()


        var tableHTML = exportedTable.outerHTML.replace(/ /g, '%20');



        // Specify file name
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>
<?= $this->endSection() ?>