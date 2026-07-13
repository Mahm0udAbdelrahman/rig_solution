@if(Route::currentRouteName() == "defect.show")
    <div class="row row-flex jjccf" >
        <div class="col-4 p-0 border-dark black" style="padding-top: 0 !important; border-right: none;">
            <h6 class="bg-dark white text-center mb-0 text-bold-600 border-dark"> Company Appointed Examiner</h6>
                <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Name : {{$person_make_report}}</p>
                <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Qualification : {{$person_make_report_desc}}</p>
                <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Date: {{$report_created_at}}</p>
        </div>
        <div class="col-2 p-0 border-dark" style="padding-top: 0 !important;  border-left: none;">
            <h6 class="bg-dark white text-center text-bold-600 mb-0 border-dark"> Signature</h6>
            <div class="text-center middle h-130" style="justify-content: center;">
                <img class="media-object" style="max-width: 85%; margin: auto;" src="{{Storage::url('employees/')}}{{$esign}}" alt="" />
            </div>
        </div>
        <div class="col-4 p-0 border-dark black" style="padding-top: 0 !important; border-right: none;">
            <h6 class="bg-dark white text-center text-bold-600 mb-0 border-dark"> Client Representative</h6>
            <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Name : ------------------------------------------------------------</p>
            <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Title : ---------------------------------------------------------------</p>
            <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Date: ---------------------------------------------------------------</p>
        </div>
        <div class="col-2 p-0 border-dark" style="padding-top: 0 !important; border-left: none;">
            <h6 class="bg-dark white text-center text-bold-600 mb-0 border-dark"> Signature</h6>
            <div class="text-center middle h-130" style="justify-content: center;">
            </div>
        </div>
    </div>
@else
<div class="row">
    <p class="col-12 border-dark black" style="line-height: 16px;">This Inspection was carried out in compliance with ISO/IEC 17020 & ILAC P15 Rig Solutions confirms that all information obtained or created during its inspection activities is kept confidential by all personnel acting on its behalf</p>
</div>
<div class="row row-flex jjccf" >
    <div class="col-4 p-0 border-dark black" style="padding-top: 0 !important;">
        <h6 class="bg-dark white text-center mb-0 text-bold-600 border-dark"> Company Appointed Examiner</h6>
        <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Name : {{$person_make_report}}</p>
        <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Qualification : {{$person_make_report_desc}}</p>
        <p class="pl-1" style="padding-top: 5px; padding-bottom: 5px;">Date: {{$report_created_at}}</p>
    </div>
    <div class="col-2 p-0 border-dark" style="padding-top: 0 !important;">
        <h6 class="bg-dark white text-center text-bold-600 mb-0 border-dark"> Signature</h6>
        <div class="text-center middle h-130" style="justify-content: center;">
          	<img class="media-object" style="max-width: 85%; margin: auto;" src="{{Storage::url('employees/')}}{{$esign}}" alt="" />
        </div>
    </div>
    <div class="col-2 p-0 border-dark" style="padding-top: 0 !important;">
        <h6 class="bg-dark white text-center text-bold-600 border-dark" style="margin-bottom: 0;"> QR Code</h6>
        <div class="text-center " style="justify-content: center; padding: 10px 0;">
          	<?php echo '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(URL($pdfurl), 'QRCODE') . '" alt="barcode" />'; ?>
       </div>
    </div>
    <div class="col-4 p-0 border-dark" style="padding-top: 0 !important;">
        <h6 class="bg-dark white text-center text-bold-600 mb-0 border-dark"> Person authenticating this report</h6>
        <div class="text-center black">
	          @if($user_id_approved==Null)
                    @if($hasPermission)
                        <button type="button" id="approve" class="btn btn-warning btn-print btn-lg mr-1 waves-effect waves-light approve"><i class="la la-paper-plane-o mr-50"></i>Approve</button>
                    @else
                        <p class="pl-1 mb-0 text-16 black info mt-2" style="padding-top: 5px; padding-bottom: 5px;">Waiting For Approve</p>
                    @endif
	          @else
                    <p class="pl-1 mb-0 text-16 black mt-0" style="padding-top: 5px; padding-bottom: 5px;">
                        {{App\Models\User::findOrFail($user_id_approved)->employee->name}}
                        <img class="media-object" style="max-width: 50%; margin: auto;" src="{{Storage::url('employees/')}}{{App\Models\User::findOrFail($user_id_approved)->employee->esign}}" alt="" />
                    </p>
	          @endif
        </div>
    </div>
</div>
@endif
