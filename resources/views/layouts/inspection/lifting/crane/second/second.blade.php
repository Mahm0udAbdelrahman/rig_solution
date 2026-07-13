@extends('layouts.styles.second-v2')

@push('page_content_second')
		<div class="row page_in">
				<div class="col-8 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Main Wire Rope</h6>
						<div class="row" style="margin-left: 0; margin-right: 0;">
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">ID Number</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_1}}</p>
								</div>
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Description</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_4}}</p>
								</div>
						</div>
				</div>
				<div class="col-2 p-0 border-dark" style="padding-top: 0!important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Safe Working Load</h6>
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_2}}</p>
				</div>
				<div class="col-2 p-0 border-dark" style="padding-top: 0!important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Condition</h6>
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_3}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-2 p-0 bg-dark border-dark">
						<h6 class=" white text-bold-600 pl-0 text-center mb-0 emadh6">Name of certifying body</h6>
				</div>
				<div class="col-2 p-0 border-dark">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_5}}</p>
				</div>
				<div class="col-2 p-0 border-dark bg-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Certificate Number</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_6}}</p>
				</div>
				<div class="col-2 p-0 border-dark bg-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Date of Test</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_7}}</p>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of defects found which are or could become a danger to person or none</h6>
				</div>
				<div class="border-dark col-8 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_8}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-8 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Main Block/Hook</h6>
						<div class="row" style="margin-left: 0; margin-right: 0;">
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">ID Number</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_9}}</p>
								</div>
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Description</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_12}}</p>
								</div>
						</div>
				</div>
				<div class="col-2 p-0 border-dark" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Safe Working Load</h6>
						<p class=" pl-1 mb-0 text-16 black">{{$crane2->lcr2_10}}</p>
				</div>
				<div class="col-2 p-0 border-dark" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Condition</h6>
						<p class=" pl-1 mb-0 text-16 black">{{$crane2->lcr2_11}}</p>
				</div>
		</div>
		<div class="row">
				<div class="bg-dark col-2 p-0 border-dark">
						<h6 class=" white text-bold-600 pl-0 text-center mb-0 emadh6">Name of certifying body</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_13}}</p>
				</div>
				<div class="col-2 p-0 bg-dark border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Certificate Number</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class=" pl-1 mb-0 text-16 black">{{$crane2->lcr2_14}}</p>
				</div>
				<div class="col-2 p-0 bg-dark border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Date of Test</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class=" pl-1 mb-0 text-16 black">{{$crane2->lcr2_15}}</p>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of defects found which are or could become a danger to person or none</h6>
				</div>
				<div class="border-dark col-8 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_16}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-8 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Auxiliary wire rope</h6>
						<div class="row" style="margin-left: 0; margin-right: 0;">
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">ID Number</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_17}}</p>
								</div>
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Description</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_20}}</p>
								</div>
						</div>
				</div>
				<div class="border-dark col-2 p-0" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Safe Working Load</h6>
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_18}}</p>
				</div>
				<div class="border-dark col-2 p-0" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Condition</h6>
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_19}}</p>
				</div>
		</div>
		<div class="row">
				<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-0 text-center mb-0 emadh6">Name of certifying body</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_21}}</p>
				</div>
				<div class="col-2 p-0 bg-dark border-dark ">
						<h6 class="white text-bold-600 pl-1 mb-0">Certificate Number</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_22}}</p>
				</div>
				<div class="col-2 p-0 bg-dark border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Date of Test</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_23}}</p>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-4 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Particulars of defects found which are or could become a danger to person or none</h6>
				</div>
				<div class="border-dark col-8 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_24}}</p>
				</div>
		</div>
		<div class="row">
				<div class="col-8 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Auxiliary Block/Hook ( Overhaul ball)</h6>
						<div class="row" style="margin-left: 0; margin-right: 0;">
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">ID Number</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_25}}</p>
								</div>
								<div class="col-6 p-0">
										<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Description</h6>
										<p class="border-dark pl-1 mb-0 text-16 black">{{$crane2->lcr2_28}}</p>
								</div>
						</div>
				</div>
				<div class="border-dark col-2 p-0" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Safe Working Load</h6>
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_26}}</p>
				</div>
				<div class="border-dark col-2 p-0" style="padding-top: 0 !important;">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Condition</h6>
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_27}}</p>
				</div>
		</div>
		<div class="row">
				<div class="bg-dark col-2 p-0 border-dark">
						<h6 class="white text-bold-600 pl-0 text-center mb-0 emadh6">Name of certifying body</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_29}}</p>
				</div>
				<div class="col-2 p-0 bg-dark border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Certificate Number</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_30}}</p>
				</div>
				<div class="col-2 p-0 bg-dark border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Date of Test</h6>
				</div>
				<div class="border-dark col-2 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_31}}</p>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-4 p-0 bg-dark border-dark">
						<h6 class="white text-bold-600 pl-1 mb-0">Particulars of defects found which are or could become a danger to person or none</h6>
				</div>
				<div class="border-dark col-8 p-0">
						<p class="pl-1 mb-0 text-16 black">{{$crane2->lcr2_32}}</p>
				</div>
		</div>
		<h6 class="bg-dark white text-bold-600 p-0 pl-1 mb-0 row border-dark">MPI Details ( Inspection Method and equipment used )</h6>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-8 p-0">
						<div class="row" style="margin: auto;">
								<h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Standard:</h6>
								<p class="col-3 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_33}}</p>
								<h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Equipment type</h6>
								<p class="col-3 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_34}}</p>
						</div>
						<div class="row" style="margin: auto;">
								<h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Equipment No</h6>
								<p class="col-3 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_35}}</p>
								<h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Due Date</h6>
								<p class="col-3 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_37}}</p>
						</div>
						<div class="row" style="margin: auto;">


								<h6 class="bg-dark white col-3 p-0 pl-1 mb-0 border-dark">Pole spacing</h6>
								<p class="col-9 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_36}}</p>
						</div>
				</div>
				<div class=" col-4 p-0">
						<div class="row" style="margin: auto;">
								<h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark" style="padding-top: 2px !important; padding-bottom: 1px !important;">Solution details</h6>
								<h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark" style="padding-top: 2px !important; padding-bottom: 1px !important;">Contrast</h6>
								<h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark" style="padding-top: 2px !important; padding-bottom: 1px !important;">Indicator</h6>
						</div>
						<div class="row" style="margin: auto;">
								<h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark">Manufacturer</h6>
								<p class="col-4 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_38}}</p>
								<p class="col-4 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_39}}</p>
						</div>
						<div class="row" style="margin: auto;">
								<h6 class="bg-dark white col-4 p-0 pl-1 mb-0 border-dark">Expire Date</h6>
								<p class="col-4 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_40}}</p>
								<p class="col-4 p-0 pl-1 border-dark text-16 black">{{$crane2->lcr2_41}}</p>
						</div>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-12 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Final Conclusion:</h6>
						<p class="border-dark pl-1 mb-0 text-16 black" style="height: 75px;">{!!nl2br(html_entity_decode($crane2->lcr2_42))!!}</p>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-12 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Guide Instructions</h6>
						<p class="border-dark pl-1 mb-0 text-16 black " style="height: 75px;">{!!nl2br(html_entity_decode($crane2->lcr2_43))!!}</p>
				</div>
		</div>
		<div class="row" style="margin-bottom: 3px;">
				<div class="col-12 p-0">
						<p class="border-dark pl-1 mb-0 black">
								I hereby certify that the crane described in this certificate was tested with accessories gears by a competent person in a manner set forth on the
								reverse side of this certificate; that a careful examination of the said machinery and gear by a competent person after the test showed it had
								withstood with the proof load without injury or permanent deformation and that the Safe Working load of the above describe machinery and gears
								is as shown in in column (4) in load test details table (page 1).
						</p>
				</div>
		</div>
		@include('layouts.styles.reportfooter-v2')
@endpush
