<?= $this->extend('admin/layouts/default') ?>
<?= $this->section('content') ?>

    <!-- begin:: Subheader -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    <?= $title; ?> </h3>
                <span class="kt-subheader__separator kt-hidden"></span>

            </div>

        </div>
    </div>

    <!-- end:: Subheader -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid" style="padding:0px; margin:0px;">
        <div class="kt-portlet">
            <?php /*
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    <?= $title; ?> List
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">

                            &nbsp;
                            <a href="<?= route_to('usulan/tambah-baru') ?>" class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-plus"></i>
                              Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            */?>
            <div class="kt-portlet__body" style="padding:0px;">
                <?php /*
                <div class="kt-form kt-form--label-right kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-xl-12 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">

                                </div>
                                <!-- <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">

                                </div> -->
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">
                                            <label>Urutkan:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control bootstrap-select" onchange="javascript:location.href = this.value;" id="kt_form_type">
                                                <option value="<?= site_url('datapaket'); ?>" <?php if($sort ==='desc') echo 'selected' ?>>Terbaru</option>
                                                <option value="<?= site_url('datapaket?sort=asc'); ?>"  <?php if($sort ==='asc') echo 'selected' ?>>Terlama</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                 */?>

                <!--begin::Section-->
                <div class="kt-section">
                    <?php /*
                    <div class="kt-section__info">
                    <?= view('admin/partials/notifications') ?>
                    </div>
                    */ ?>
                    <div class="kt-section__content">
                        
                    </div>
                </div>

                <!--end::Section-->
            </div>

            <!--end::Form-->
        </div>
    </div>

<script>
var colspan=<?php echo $colspan;?>;
function getUrlVars(param=null)
{
	if(param !== null)
	{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
        if( typeof vars[param] === 'undefined' ) return null;
		else return vars[param];
	}
	else
	{
		return null;
	}
}

// alert(getUrlVars('id') + '-' + getUrlVars('a'));
function gdata(tag, id){
        //alert($("#a-go-"+id).attr('abuka'));
        var ago=$('#a-go-'+tag+'-'+id);
        var gobox=$('#go-'+tag+'-'+id);
        if(ago.attr('abuka')==="true"){
            var tagL='';
            if(tag=='balai')tagNext='satker';
            if(tag=='satker')tagNext='paket';
            var tbody=$('#tbody-'+tag+'-'+id);
            tbody.html('');
            $.ajax({
                type : 'GET',
                url  : "<?php echo site_url('pulldata/');?>get"+tagNext+"/"+id,
                dataType : 'json',
                beforeSend:function(){
                    //proses
                    tbody.html('<tr><td colspan="'+colspan+'" style="text-align:center; background-color:rgba(38,36,37,0.65); color:#fff; padding:3px;"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>Sedang memuat data...</td></tr>');
                },
                success: function (data) {
                    // var d = JSON.parse(data)
                    if(data.qdata.length>0){
                        if(tag=='balai'){
                            tdUtama({id:id, tag:tag, tagNext:tagNext}, data);
                        }
                        else{
                            tdPaket({id:id, tag:tag, tagNext:tagNext}, data);
                        }
                        ago.attr('abuka', 'false');
                    }
                    else{
                        tbody.html('<tr><td colspan="'+colspan+'" style="text-align:center; background-color:rgba(38,36,37,0.65); color:#fff; padding:3px;">Data tidak ditemukan...</td></tr>');
                        ago.attr('abuka', 'false');
                        gobox.removeClass('show').addClass('show');
                        ago.attr('abuka', 'true');
                    }
                }
            });
        }
        return false;
}
function tdUtama(t, v){
    var tbody=$('#tbody-'+t.tag+'-'+t.id);
    tbody.html('');

    $.each(v.qdata, function(key, d) {
        tbody.append('<tr class="stw'+d.stw+'">'
            +'<td class="tdKodeLabel" colspan="6">'
            +'<a onclick="gdata(\''+t.tagNext+'\', \''+d.id+'\')" '
            +'id="a-go-'+t.tagNext+'-'+d.id+'" data-toggle="collapse" aria-expanded="true" aria-controls="go-'+t.tagNext+'-'+d.id+'" class="" '
            +'abuka="true"'
            +'href="#go-'+t.tagNext+'-'+d.id+'">'
            //+'.'href="#?id='+d.id+'">'
            +d.id+' '+d.label
            +'</a>'
            +'</td>'

            +'<td class="text-right tdNilai">'+ nf((d.jml_pagu_rpm /1000))+'</td>'
            +'<td class="text-right tdNilai">'+ nf((d.jml_pagu_sbsn/1000))+'</td>'
            +'<td class="text-right tdNilai">'+ nf((d.jml_pagu_phln/1000))+'</td>'
            +'<td class="text-right tdNilai">'+ nf((d.jml_pagu_total/1000))+'</td>'

            +'<td class="text-right tdNilai">'+ nf((d.jml_real_total/1000))+'</td>'

            +'<td class="text-right tdPersen">'+ nf(d.jml_progres_keuangan,2)+'</td>'
            +'<td class="text-right tdPersen">'+ nf(d.jml_progres_fisik,2)+'</td>'

            +'<td class="text-right tdPersen">'+ ( parseInt(d.jml_progres_fisik) > parseInt(d.jml_progres_keuangan) ? nf(d.jml_persen_deviasi,2) : '-')+'</td>'
            +'<td class="text-right tdPersen">'+ ( parseInt(d.jml_progres_fisik) > parseInt(d.jml_progres_keuangan) ? nf((d.jml_nilai_deviasi/1000)) : '-')+'</td>'

        +'</tr>');

        tbody.append( tblbox(t.tagNext, d) );
    })
    return false;
}
function tblbox(tagNext, d){
    return ''
    +'<tr>'
        +'<td colspan="'+colspan+'" style="padding:0px; border:0px;">'
            +'<div id="go-'+tagNext+'-'+d.id+'" class="in collapse" role="tabpanel" aria-labelledby="a-go-'+tagNext+'-'+d.id+'">'
                +'<table class="table table-bordered" style="padding:0px; margin:0px; border:0px;">'
                    +'<tbody id="tbody-'+tagNext+'-'+d.id+'">'
                        // +'<tr>'
                        //     +'<td class="tdLabelFull" colspan="10">tbody-'+tagNext+'-'+d.id+' -- '+d.label+'</td>'
                        // +'</tr>'
                    +'</tbody>'
                +'</table>'
            +'</div>'
        +'</td>'
    +'</tr>';
}
function tdPaket(t, v){
    var tbody=$('#tbody-'+t.tag+'-'+t.id);
    tbody.html('');


    var idp=[]; var idg=[]; var ido=[]; var idso=[]; var idkk=[];
    $.each(v.qdata, function(key, d) {
        var idk=d.programid;
        // if( inArray( idk, idp ) ==false ){
        //     tbody.append('<tr><td class="tdLabelFull" colspan="'+colspan+'">'+d.programid +'</td></tr>');
        //     idp = $.merge( [ idk ], idp );
        // }
        var idk=idk+'-'+d.giatid;
        if( inArray( idk, idg ) ==false ){
        tbody.append('<tr class="tdgiat"><td class="tdKodeLabel" colspan="6">'+d.programid +'.'+d.giatid +'</td> '+ tdKomenklatur('giat', idk) +' </tr>');
            idg = $.merge( [ idk ], idg );
        }
        var idk=idk+'-'+d.outputid;
        if( inArray( idk, ido ) ==false ){
        tbody.append('<tr class="tdoutput"><td class="tdKodeLabel" colspan="6">'+d.programid +'.'+d.giatid +'.'+d.outputid +'</td> '+ tdKomenklatur('output', idk) +' </tr>');
            ido = $.merge( [ idk ], ido );
        }
        var idk=idk+'-'+d.soutputid;
        if( inArray( idk, idso ) ==false ){
        tbody.append('<tr class="tdsoutput"><td class="tdKodeLabel" colspan="6">'+d.programid +'.'+d.giatid +'.'+d.outputid +'.'+d.soutputid +'</td> '+ tdKomenklatur('soutput', idk) +' </tr>');
            idso = $.merge( [ idk ], idso );
        }
        var idk=idk+'-'+d.komponenid;
        if( inArray( idk, idkk ) ==false ){
        tbody.append('<tr class="tdkomponen"><td class="tdKodeLabel" colspan="6">'+d.programid +'.'+d.giatid +'.'+d.outputid +'.'+d.soutputid +'.'+d.komponenid +'</td> '+ tdKomenklatur('komponen', idk) +' </tr>');
            idkk= $.merge( [ idk ], idkk );
        }

        tbody.append(''
            +'<tr class="stw'+d.stw+'">'
            +'<td class="tdKode">'+d.id+'</td>'
            +'<td class="tdLabel">'+d.label+'</td>'
            +'<td class="tdTV">'+d.vol+'</td>'
            +'<td class="tdLokasi">'+d.lokasi+'</td>'
            +'<td class="tdJP">'+d.jenis_paket+'</td>'
            +'<td class="tdMP">'+d.metode_pemilihan+'</td>'

            +'<td class="text-right tdNilai">'+ nf((d.pagu_rpm/1000)) +'</td>'
            +'<td class="text-right tdNilai">'+ nf((d.pagu_sbsn/1000)) +'</td>'
            +'<td class="text-right tdNilai">'+ nf((d.pagu_phln/1000)) +'</td>'
            +'<td class="text-right tdNilai">'+ nf((d.pagu_total/1000)) +'</td>'

            +'<td class="text-right tdNilai">'+ nf((d.real_total/1000)) +'</td>'

            +'<td class="text-right tdPersen">'+ nf(d.progres_keuangan,2) +'</td>'
            +'<td class="text-right tdPersen">'+ nf(d.progres_fisik,2) +'</td>'

            +'<td class="text-right tdPersen">'+ ( parseInt(d.progres_fisik) > parseInt(d.progres_keuangan) ?  nf(d.persen_deviasi,2) : '-')+'</td>'
            +'<td class="text-right tdNilai">' + ( parseInt(d.progres_fisik) > parseInt(d.progres_keuangan) ?  nf((d.nilai_deviasi/1000)) : '-') +'</td>'
            +'</tr>'
        );
    })
    return false;
}
function tdKomenklatur(tag, idk){

    var td ='<td class="tdNilai rpm-'+tag+'-'+idk+'">&nbsp;</td>'
    +'<td class="tdNilai sbsn-'+tag+'-'+idk+'">&nbsp;</td>'
    +'<td class="tdNilai phln-'+tag+'-'+idk+'">&nbsp;</td>'
    +'<td class="tdNilai pagu_total-'+tag+'-'+idk+'">&nbsp;</td>'

    +'<td class="tdNilai real_total-'+tag+'-'+idk+'">&nbsp;</td>'

    +'<td class="tdPersen progres_keuangan-'+tag+'-'+idk+'">&nbsp;</td>'
    +'<td class="tdPersen progres_fisik-'+tag+'-'+idk+'">&nbsp;</td>'

    +'<td class="tdPersen persen_deviasi-'+tag+'-'+idk+'">&nbsp;</td>'
    +'<td class="tdPersen nilai_deviasi-'+tag+'-'+idk+'">&nbsp;</td>';
    return td;
}
</script>

<script>
function nf(a, d=0){
	a   = parseFloat(a).toFixed(d);
    split   = a.split('.'),
	sisa    = split[0].length % 3,
	n  = split[0].substr(0, sisa),
	ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

	if(ribuan){
		separator = sisa ? '.' : '';
		n += separator + ribuan.join('.');
	}
    n = split[1] != undefined ? n + ',' + split[1] : n;
	return n;
}
function arrayCompare(a1, a2) {
    if (a1.length != a2.length) return false;
    var length = a2.length;
    for (var i = 0; i < length; i++) {
        if (a1[i] !== a2[i]) return false;
    }
    return true;
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(typeof haystack[i] == 'object') {
            if(arrayCompare(haystack[i], needle)) return true;
        } else {
            if(haystack[i] == needle) return true;
        }
    }
    return false;
}
</script>

    <!-- end:: Content -->
<?= $this->endSection() ?>
<?= $this->section('footer_js') ?>
    <script>
        console.log('additional footer js')
    </script>
<?= $this->endSection() ?>