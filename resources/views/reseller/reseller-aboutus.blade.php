@extends('layout.reseller.reseller-sidebar-layout')

@section('breadcrumb')
<a class="navbar-brand" href="/vendor/about-us">Update About Us<div class="ripple-container"></div></a>
@endsection

@section('content')
<div class="content custom-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary reseller-color-bg">
                        <h4 class="card-title ">About Us</h4>
                        {{-- <p class="card-category"> Resellers pending for verification</p> --}}
                    </div>
                    <div class="card-body">
                        <form method="POST" id="update_aboutus_frm">
                            {{-- <textarea id="summernote" name="about_us" rows="3"></textarea> --}}
                            <input type="hidden" id="about_us" value="{{$data}}">
                            <div id="summernote"></div>
                            <button type="submit" id="btn_submit" class="btn btn-success reseller-color-bg pull-right">Save</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('script')
<script>
    $('#profile_item').addClass('active');
    $('#profile_item').find('.collapser').addClass('collapsed');
    $('#profile_item').find('.collapse').addClass('show');
    $('#profile_item').find('#reseller_aboutus_subitem').addClass('active');
</script>

<script>
    $(document).ready(function() {
        
        
        $('#summernote').summernote({
            placeholder: "About Us",
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
        $('#summernote').summernote('code', $("#about_us").val());
        
    });

    $('#update_aboutus_frm').on('submit', function(e) {
        e.preventDefault();
        // console.log($('#summernote').summernote('code'));
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT",
            url: '/vendor/about-us/update',
            data: {about_us: $('#summernote').summernote('code')},   
            dataType: 'json',   
            success: function(result){

                if (result.status == "error") {
                    modalAlert({"type":"error","message":"", 
                        "action": function(){ 
                            location.reload(); 
                        }
                    });
                }else if(result.status == "sucess") {
                    modalAlert({"type":"success","message":"", 
                        "action": function(){ 
                            location.reload(); 
                        }
                    });
                }
                
            }
        });
    }); 
    
</script>


@endsection 