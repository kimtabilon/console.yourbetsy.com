@extends('layout.reseller.reseller-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/vendor/shipment-rate">Shipment Rate<div class="ripple-container"></div></a>
@endsection

@section('content')
<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">Tablerate Per SKU</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 custom-button-container-center">
                                <button type="submit" id="btn_submit" onclick="csv_export()" class="btn btn-info custom-button-center">Export Template</button>
                            </div>
                            
                            <div class="col-md-6">
                                <form method="POST" id="frm_shipping_rate_import">
                                    <div class="row file-upload-container">
                                        <div class="col-md-6" style="margin-bottom: 1rem;">
                                            <div class="custom-file">
                                                {{-- <input type="file" class="custom-file-input" id="customFile"> --}}
                                                <input class="file-upload-input custom-file-input" type="file" id="customFile" name="csv" accept=".csv" />
                                                <label class="custom-file-label" for="customFile">Choose csv file</label>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" id="btn_submit" class="btn btn-success reseller-color-bg custom-button-center">Import</button>
                                </form>
                                
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('script')
<script>
    $('#shipment_rate').addClass('active');
</script>

<script>
    $(document).ready(function() {
        /* $('#summernote').summernote({
            placeholder: "Shipping Policy",
            height: 300,
            focus: true,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
        });
        $('#summernote').summernote('code', $("#shipping_policy").val()); */
        
    });

    function csv_export() {
        window.location.href = "{{url('/')}}/vendor/shipment-rate/export";
    }

    $('#frm_shipping_rate_import').on('submit', function(e) {
        saving_modal("show");

        var formData = new FormData($('#frm_shipping_rate_import')[0]);
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/vendor/shipment-rate/import',
            data: formData,   
            dataType: 'json',
            processData: false,
			contentType: false,
            success: function(result){
                if (result.status == "error") {
                    modalAlert({"type":"error","message":'Nothing to import', 
                        "action": function(){ 
                            saving_modal("hide"); 
                        }
                    });
                }else if(result.status == "success") {
                    setTimeout(function () {
                        saving_modal("hide");
                        modalAlert({"type":"success","message":"", 
                            "action": function(){ 
                                location.reload(); 
                            }
                        });
                    }, 3000);
                }
            }
        });
    // }
    });
</script>


@endsection 