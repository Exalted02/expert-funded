@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ url('front-assets/plugins/c3-chart/c3.min.css') }}">
@endsection
@section('content')
@php 
$data = App\Models\Kyc_documents::where('client_id', auth()->user()->id)->first();
@endphp
    <!-- Page Wrapper -->
    <div class="page-wrapper">
    
        <!-- Page Content -->
        <div class="content container-fluid pb-0">
            <div class="row">
                <div class="col-lg-12">
					<div class="card employee-month-card flex-fill">
						<div class="card-body">
							<div class="statistic-header">
								<h4>Verification Of Identity</h4>
							</div>
							<hr class="mt-0">
							<small>Your can submit your documents here</small>
							@if(session('success'))
								<div class="alert alert-success">
									{{ session('success') }}
								</div>
							@endif

							<form name="frm" action="{{ route('client.verification') }}" enctype="multipart/form-data" method="post">
							@csrf
							<div class="row mt-3 identity-verification">
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Frontal View (jpg, png or pdf)</small>
											@if(empty($data->frontal))
											<label class="dropzone hid-frontal-div">
												<div class="upload-content">
													<i class="la la-file-upload frontal-la"></i>
													<span id="frontal-upl">UPLOAD HERE</span>
													
												</div>
												<input type="file" hidden name="frontal_view_file" id="frontal_view_file">
											</label>
											@else
											<div id="frontalShowContainer" style="margin-top: 10px;">
												<img id="frontalShowFile" src="{{ url('uploads/kyc/' .auth()->user()->id .'/frontal/'.$data->frontal)}}">
											<a href="#" id="removeFrontalShowFile">×</a>
											</div>
											@endif
											<div id="frontalPreviewContainer" style="margin-top: 10px;">
											<img id="frontalPreview" src="">
											<a href="#" id="removeFrontalPreview">×</a>
											</div>
										</div>
										
									</div>
									@if ($errors->has('frontal_view_file'))
										<span class="text-danger">{{ $errors->first('frontal_view_file') }}</span>
									@endif
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Back View (jpg, png or pdf)</small>
											<label class="dropzone hid-back-div">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden name="back_view_file" id="back_view_file">
											</label>
											<div id="backPreviewContainer" style="margin-top: 10px; display: none;">
												<img id="backPreview" src="" alt="Preview">
												<a href="#" id="removeBackPreview">×</a>
											</div>
										</div>
									</div>
									@if ($errors->has('back_view_file'))
										<span class="text-danger">{{ $errors->first('back_view_file') }}</span>
									@endif
								</div>
								<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
									<div class="card dash-widget mb-0">
										<div class="card-body">
											<small>Proof Of Residence (jpg, png or pdf)</small>
											<label class="dropzone hid-residence-div">
												<div class="upload-content">
													<i class="la la-file-upload"></i>
													<span>UPLOAD HERE</span>
												</div>
												<input type="file" hidden  name="residence_file" id="residence_file">
											</label>
											<div id="residencePreviewContainer" style="margin-top: 10px; display: none;">
												<img id="residencePreview" src="" alt="Preview">
												<a href="#" id="removeResidencePreview">×</a>
											</div>
										</div>
									</div>
									@if ($errors->has('residence_file'))
										<span class="text-danger">{{ $errors->first('residence_file') }}</span>
									@endif
								</div>
							</div>
							<button class="btn btn-primary mt-3">
								<i class="la la-file-alt"></i> Submit KYC
							</button>
							</form>
						</div>
					</div>
				</div>
			</div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

@endsection 
@section('scripts')
<!-- Chart JS -->
<script src="{{ url('front-assets/plugins/c3-chart/d3.v5.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/c3.min.js') }}"></script>
<script src="{{ url('front-assets/plugins/c3-chart/chart-data.js') }}"></script>
<script>

document.getElementById("frontal_view_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('#frontalPreview').show();
	$('.hid-frontal-div').hide();
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("frontalPreview").src = e.target.result;
            document.getElementById("frontalPreviewContainer").style.display = "block";
            document.getElementById("removeFrontalPreview").style.display = "flex"; // Show remove link
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById("removeFrontalPreview").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
	$('.hid-frontal-div').show();
    document.getElementById("frontalPreviewContainer").style.display = "none"; // Hide preview container
    document.getElementById("removeFrontalPreview").style.display = "none"; // Hide remove link
    document.getElementById("frontalPreview").src = ""; // Clear preview image
    document.getElementById("frontal_view_file").value = ""; // Reset file input
});

document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        let removeButton = document.getElementById("removeFrontalShowFile");

        if (removeButton !== null) {
            removeButton.addEventListener("click", function(event) {
                event.preventDefault();
                alert('ok'); // Debugging - Ensure event is firing

                let frontalContainer = document.getElementById("frontalShowContainer");
                let frontalImage = document.getElementById("frontalShowFile");
                let fileInput = document.getElementById("frontal_view_file");

                if (frontalContainer) {
                    frontalContainer.style.display = "none";
                }
                if (frontalImage) {
                    frontalImage.src = "";
                }
                if (fileInput) {
                    fileInput.value = "";
                }
            });
        } else {
            console.log("Element #removeFrontalShowFile not found in DOM.");
        }
    }, 500); // Delay execution to ensure element is available
});

/*document.addEventListener("DOMContentLoaded", function() {
    let removeButton = document.getElementById("removeFrontalShowFile");

    if (removeButton) {
		
        removeButton.addEventListener("click", function(event) {
            event.preventDefault(); 
			alert('ok');
            document.getElementById("frontalShowContainer").style.display = "none";
            document.getElementById("frontalShowFile").src = ""; 
            let fileInput = document.getElementById("frontal_view_file");
            if (fileInput) {
                fileInput.value = ""; 
            }
        });
    }
});*/



/*document.getElementById("frontal_view_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('#frontalPreview').show();
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("frontalPreview").src = e.target.result;
            document.getElementById("frontalPreviewContainer").style.display = "block";
        };
        reader.readAsDataURL(file);
    }
});*/

document.getElementById("back_view_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('.hid-back-div').hide();
	if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("backPreview").src = e.target.result;
            document.getElementById("backPreviewContainer").style.display = "block"; document.getElementById("removeBackPreview").style.display = "flex";
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById("removeBackPreview").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
	$('.hid-back-div').show();
    document.getElementById("backPreviewContainer").style.display = "none"; // Hide preview container
    document.getElementById("removeBackPreview").style.display = "none"; // Hide remove link
    document.getElementById("backPreview").src = ""; // Clear preview image
    document.getElementById("back_view_file").value = ""; // Reset file input
});


document.getElementById("residence_file").addEventListener("change", function(event) {
    let file = event.target.files[0];
	$('.hid-residence-div').hide();
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("residencePreview").src = e.target.result;
            document.getElementById("residencePreviewContainer").style.display = "block";
			document.getElementById("removeResidencePreview").style.display = "flex";
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById("removeResidencePreview").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default link behavior
	$('.hid-residence-div').show();
    document.getElementById("residencePreviewContainer").style.display = "none"; // Hide preview container
    document.getElementById("removeResidencePreview").style.display = "none"; // Hide remove link
    document.getElementById("residencePreview").src = ""; // Clear preview image
    document.getElementById("residence_file").value = ""; // Reset file input
});
</script>

@endsection

