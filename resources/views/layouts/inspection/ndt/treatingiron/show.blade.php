@extends('layouts.app')

@extends('layouts.styles.paper-v2')

@push('page_content')
		<div class="row page_in">
				<div class="col-6 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Name of employer for whom the examination was made </h6>
						<p class="border-dark pl-1 mb-0 text-16 black">
								@if($treatingIron->job_request->client)
										{{$treatingIron->job_request->client->name}}
								@else
										{{$treatingIron->job_request->supplier->name}}
								@endif
						</p>
				</div>
				<div class="col-6 p-0">
						<h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark">Address of premises at examination was made:</h6>
						<p class="border-dark pl-1 mb-0 text-16 black">
								@if($treatingIron->job_request->client)
										{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $treatingIron->job_request->client->location)}}
								@else
										{{preg_replace('~[\\\\/:*?"<>[]|]~', '', $treatingIron->job_request->supplier->location)}}
								@endif
						</p>
				</div>
		</div>
		<div class="row">
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Purchase Order</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">
					{{$treatingIron->job_request->purchase_order ? $treatingIron->job_request->purchase_order : $treatingIron->ntir_2}}
				</div>
				<div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">JCF Number</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$treatingIron->job_request->code}}</div>
				<div class="bg-dark col-2 p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Report No</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black" data-type="code" data-id="{{$treatingIron->id}}">
						{{$treatingIron->job_request->code}} / {{ preg_replace('/(?:\s*-\s*Duplicated)+$/i', '', (string) $treatingIron->code) ?: $treatingIron->code }}
				</div>
		</div>
		<div class="row">
        <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Work location</h6></div>
			<div class="col-2 p-0 pl-1 border-dark text-16 black">
				{{$treatingIron->job_request->clientDepartment ?
                  $treatingIron->job_request->clientDepartment->name . ' / '.$treatingIron->job_request->deploc
                  : $treatingIron->job_request->deploc}}
			</div>
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Examination Date</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$treatingIron->ntir_6}}</div>
				<div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Next Exa. Date</h6></div>
				<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$treatingIron->ntir_7}}</div>
		</div>
    <div class="row">
      <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Inspection Method</h6></div>
				<div class="col-10 border-dark">
					<div class="row skin skin-square">
			<div class="col-4 p-0 pl-1">
          <span class="noncheckedfrom @if(in_array('visual-Examination', json_decode($treatingIron->ntir_9))) checked @endif"></span>
          <label style="margin-right: 0.6rem;">Visual Examination</label>
      </div>
      <div class="col-4 p-0 pl-1">
          <span class="noncheckedfrom @if(in_array('mpi-inspection', json_decode($treatingIron->ntir_9))) checked @endif"></span>
          <label>MPI Inspection</label>
      </div>
      <div class="col-4 p-0 pl-1">
          <span class="noncheckedfrom @if(in_array('ut-wall-thickness', json_decode($treatingIron->ntir_9))) checked @endif"></span>
          <label>UT Wall Thickness</label>
      </div>
</div>


		</div>

		</div>

<!-- 		<div class="row">
		    <div class="col-2 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Specification</h6></div>
		    <div class="col-5 p-0 pl-1 border-dark text-16 black">
		        <div class="row skin skin-square">
		            @foreach(json_decode($treatingIron->ntir_10) as $key => $spec)
		                <div class="col-md-4 col-sm-12 mid">
		                    <span class="noncheckedfrom checked"></span>
		                    <label for="{{$spec}}" @if($spec == 'customer-specification') style="font-size: 60%;" @endif>{{str_replace('-', ' ',$spec)}}</label>
		                </div>
		            @endforeach
		        </div>
		    </div>
		    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$treatingIron->ntir_11}}</div>
				<div class="col-1 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Edition</h6></div>
		    <div class="col-2 p-0 pl-1 border-dark text-16 black">{{$treatingIron->ntir_49}}</div>
		</div> -->

		<div class="row">
			<div class="col-2 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Specification</h6>
			</div>
			<div class="col-6 p-0 pl-1 border-dark text-16 black">
				<div class="row skin skin-square">
					@foreach($specificationOptions as $key => $spec)
					@if($key != 'S-008')
					<div class="specinsp" style="margin-left: 8px;">
						<span
							class="noncheckedfrom {{in_array(strtolower(str_replace(' ', '-', $spec)), $treatingIron->specifications)? 'checked' : ''}}"
							style="position: relative;top: 3px;"></span>
						<label>{{$spec}}</label>
					</div>
					@endif
					@endforeach
					<div class="specinsp" style="margin-left: 8px;">
						<span class="noncheckedfrom {{in_array('other', $treatingIron->specifications)? 'checked' : ''}}"
							style="position: relative;top: 3px;"></span>
						<label>Other</label>
					</div>
				</div>
			</div>
			<div class="col-2 p-0 pl-1 border-dark text-16 black">{{$treatingIron->ntir_11}}</div>
			<div class="col-1 bg-dark p-0 border-dark">
				<h6 class="white text-bold-600 pl-1 mb-0 mid">Edition</h6>
			</div>
			<div class="col-1 p-0 pl-1 border-dark text-16 black">{{$treatingIron->ntir_49}}</div>
		</div>


    <div class="row">
        <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Description</h6></div>
        <div class="col-10 p-0 pl-1 border-dark text-16 black middle preline" style="height: 30px;">{{$treatingIron->desc}}</div>
    </div>
    <div class="row">
        <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Identification No</h6></div>
        <div class="col-5 p-0 pl-1 border-dark text-16 black middle">{{$treatingIron->ntir_13}}</div>
        <div class="col-2 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Type</h6></div>
        <div class="col-3 p-0 pl-1 border-dark text-16 black middle">{{$treatingIron->ntir_14}}</div>
    </div>
    <div class="row">
        <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Section A- Visual Examination:</h6></div>
    </div>
		<div class="row">
			<div class="col-6">
				<div class="row">
						<div class="col-12 p-0 pl-1 border-dark text-16 black">A-1: Dimension</div>
				</div>

				<div class="row">
					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">Length</div>
					<div class="col-4 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_15 ?? "N/A"}}</div>

					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">OD</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_16 ?? "N/A"}}</div>
				</div>

				<div class="row">

					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">ID</div>
					<div class="col-4 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_17  ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Angle (Elbow)</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_18  ?? "N/A"}}</div>
				</div>

				<div class="row">
					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">Lug</div>
					<div class="col-4 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_19  ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Other</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_20  ?? "N/A"}}</div>
				</div>

				<div class="row">
						<div class="col-12 p-0 pl-1 border-dark text-16 black">A-2: Conditions</div>
				</div>

				<div class="row">


					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">Thread</div>
					<div class="col-4 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_21  ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Nut</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_22  ?? "N/A"}}</div>
				</div>

				<div class="row">


					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">Seal Areat</div>
					<div class="col-4 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_23  ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Internal</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_24  ?? "N/A"}}</div>
				</div>

				<div class="row">


					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">Swivel</div>
					<div class="col-4 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_25  ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Straightness</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_26  ?? "N/A"}}</div>
				</div>

				<div class="row">
					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">NPST Conn.</div>
					<div class="col-10 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_27  ?? "N/A"}}</div>

				</div>

				<div class="row">


					<div class="col-2 bg-dark p-0 border-dark white text-bold-600">Other</div>
					<div class="col-10 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_28  ?? "N/A"}}</div>

				</div>

				<div class="row">
		        <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Section B- Magnetic Particle Inspection:</h6></div>
		    </div>


				<div class="row">
					<h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Equipment Used</h6>
					<p class=" white text-bold-600 p-0 pl-1 mb-0 border-dark col-3 middle">Magnet</p>
					<p class=" white text-bold-600 p-0 pl-1 mb-0 border-dark col-3 middle">UV Light</p>
					<p class=" white text-bold-600 p-0 pl-1 mb-0 border-dark col-3 middle">Coil</p>
				</div>
				<div class="row">
					<h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Equipment No.</h6>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_14', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_15', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_16', 'ntir_29')}}</p>


				</div>
				<div class="row">
					<h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Manufacturer</h6>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_17', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_18', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_19', 'ntir_29')}}</p>
				</div>
				<div class="row">
					<h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle">Calibr. Due Date</h6>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_20', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_21', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_22', 'ntir_29')}}</p>
				</div>
				<div class="row">
					<h6 class="bg-dark white text-bold-600 p-0 mb-0 border-dark col-3 middle f80" style="font-size: 80%;">Equip.Test Criteria</h6>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_23', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_24', 'ntir_29')}}</p>
					<p class="border-dark p-0 mb-0 text-12 pl-1 black col-3">{{$treatingIron->getMtvalue('nmpr_25', 'ntir_29')}}</p>
				</div>



			</div>
			<div class="col-6 p-0 pl-1 border-dark" style="height: 369px;">
					<img class="media-object" style="max-width: 95%; height: 95%; margin: 8px 8px;" src="{{Storage::url('camera/inspection/ndt/treatingiron/')}}{{$treatingIron->ntir_30}}" alt="" />
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<div class="row">
						<div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 mid">Section C- Ultrasonic wall thickness</h6></div>
				</div>

				<div class="row">
						<div class="col-12 p-0 pl-1 border-dark text-16 black">C-1: Equipment and Technique</div>
				</div>

				<div class="row">
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Model</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_31 ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Serial Number</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_32 ?? "N/A"}}</div>
				</div>
				<div class="row">
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Cable Type</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_33 ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Sound Velocity</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_34 ?? "N/A"}}</div>
				</div>

				<div class="row">
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Probe Dia.</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_35 ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Probe Frequency</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_36 ?? "N/A"}}</div>
				</div>

				<div class="row">
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Manufacturer</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_37 ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Calibration Due</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_38 ?? "N/A"}}</div>
				</div>

				<div class="row">
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Search Unit</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_39 ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Manufacturer</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_40 ?? "N/A"}}</div>
				</div>

				<div class="row">
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Couplant Type</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_41 ?? "N/A"}}</div>
					<div class="col-3 bg-dark p-0 border-dark white text-bold-600">Calibration Block</div>
					<div class="col-3 p-0 pl-1 border-dark text-12 black">{{$treatingIron->ntir_42 ?? "N/A"}}</div>
				</div>

				<div class="row">
						<div class="col-12 p-0 pl-1 border-dark text-16 black">C-2: Reading (mm)</div>
				</div>

				<table style="width: 100%; left: 0; position: absolute;">
					<thead style="width: 100%;">
					<tr class=" bg-dark p-0 border-dark white text-bold-600">

					<td style="width: 25%;">Minimum Thickness</td>
					<td style="width: 75%;">
						<table style="width: 100%;">
						<tr class="text-center">
							<td>Section A</td>
							<td>Section B</td>
							<td>Section C</td>
						</tr>
					</table>
					<table style="width: 100%;">
					<tr class="text-center">
						<td>A1</td>
						<td>A2</td>
						<td>A3</td>
						<td>B1</td>
						<td>B2</td>
						<td>B3</td>
						<td>C1</td>
						<td>C2</td>
						<td>C3</td>
					</tr>
				</table>

					</td>
					</tr>
				</thead>
				<tbody style="width: 100%;">
					@foreach((json_decode($treatingIron->ntir_43) ?? []) as $key => $value)
					<tr class="text-center">

							<td class=" border-dark white" style="width: 25%;">{{$value->ntir_433}}</td>
							<td style="width: 75%;">

								<table style="width: 100%;">
									<tr>
										<td class=" border-dark white" style="width: 11.111111111%;">{{$value->ntir_434}}</td>
										<td class=" border-dark white" style="width: 11.111111111%;">{{$value->ntir_435}}</td>
										<td class=" border-dark white" style="width: 11.111111111%;">{{$value->ntir_436}}</td>
										<td class=" border-dark white"  style="width: 11.111111111%;">{{$value->ntir_437}}</td>
										<td class=" border-dark white"  style="width: 11.111111111%;">{{$value->ntir_438}}</td>
										<td class=" border-dark white"  style="width: 11.111111111%;">{{$value->ntir_439}}</td>
										<td class=" border-dark white"  style="width: 11.111111111%;">{{$value->ntir_440}}</td>
										<td class=" border-dark white"  style="width: 11.111111111%;">{{$value->ntir_441}}</td>
										<td class=" border-dark white"  style="width: 11.111111111%;">{{$value->ntir_442}}</td>
									</tr>
								</table>
							</td>

					</tr>
					@endforeach
					@if(count((json_decode($treatingIron->ntir_43)) ?? []) < 3)
					    @for($i = 3-count( (json_decode($treatingIron->ntir_43)) ?? []); $i > 0; $i--)
							<tr>

									<td class=" border-dark white" style="width: 25%;">N/A</td>
									<td style="width: 75%;">

										<table style="width: 100%;">
											<tr class="text-center">
												<td class=" border-dark white" style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white" style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white" style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white"  style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white"  style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white"  style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white"  style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white"  style="width: 11.111111111%;">N/A</td>
												<td class=" border-dark white"  style="width: 11.111111111%;">N/A</td>
											</tr>
										</table>
									</td>

							</tr>
							@endfor
							@endif

				</tbody>

				</table>


			</div>
			<div class="col-6 p-0 pl-1 border-dark" style="height: 341px;">
					<img class="media-object" style="max-width: 95%; height: 95%; margin: 8px 8px;" src="{{Storage::url('camera/inspection/ndt/treatingiron/')}}{{$treatingIron->ntir_44}}" alt="" />
			</div>
		</div>
		<div class="row">
        <div class="col-12 bg-dark p-0 border-dark"><h6 class="white text-bold-600 pl-1 mb-0 text-center">Final Conclusion and Comment</h6></div>
    </div>
    <div class="row">
        <div class="col-4 bg-dark p-0 border-dark middle"><h6 class="white text-bold-600 pl-1 mb-0 mid">Observations/Result</h6></div>
        <div class="col-8 p-0 pl-1 border-dark text-16 black">
						<div class="row skin skin-square">
								<fieldset class="col-md-4">
									A: Visual inspection:
								</fieldset>
								<fieldset class="col-md-4">
										<span class="noncheckedfrom {{$treatingIron->checkbox_yes($treatingIron->ntir_45)}}"></span>
										<label for="hydrostatic">Pass</label>
								</fieldset>
								<fieldset class="col-md-4">
										<span class="noncheckedfrom {{$treatingIron->checkbox_no($treatingIron->ntir_45)}}"></span>
										<label for="pneumatic">Fail</label>
								</fieldset>
						</div>
						<div class="row skin skin-square">
								<fieldset class="col-md-4">
									B: MPI inspection:
								</fieldset>
								<fieldset class="col-md-4">
										<span class="noncheckedfrom {{$treatingIron->checkbox_yes($treatingIron->ntir_46)}}"></span>
										<label for="hydrostatic">Pass</label>
								</fieldset>
								<fieldset class="col-md-4">
										<span class="noncheckedfrom {{$treatingIron->checkbox_no($treatingIron->ntir_46)}}"></span>
										<label for="pneumatic">Fail</label>
								</fieldset>
						</div>
						<div class="row skin skin-square">
                <fieldset class="col-md-4">
                  C: UT Thickness:
                </fieldset>
                <fieldset class="col-md-4">
                    <span class="noncheckedfrom {{$treatingIron->checkbox_yes($treatingIron->ntir_47)}}"></span>
                    <label for="hydrostatic">Pass</label>
                </fieldset>
                <fieldset class="col-md-4">
                    <span class="noncheckedfrom {{$treatingIron->checkbox_no($treatingIron->ntir_47)}}"></span>
                    <label for="pneumatic">Fail</label>
                </fieldset>
            </div>
        </div>
    </div>

		<div class="row border-dark" style="margin-bottom: 3px;">
		  <h6 class="bg-dark white text-bold-600 pl-1 mb-0 border-dark col-2 middle">Final Conclusion:</h6>

		  <div class="col-6">
		      <p class="black bnew">Final result Accept / Reject ?</p>
		  </div>
		  <div class="col-2 mid">
		      <span class="noncheckedfrom {{$treatingIron->checkbox_yes($treatingIron->ntir_48)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
		      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Accept</label>
		  </div>
		  <div class="col-2 mid">
		      <span class="noncheckedfrom {{$treatingIron->checkbox_no($treatingIron->ntir_48)}}" style="width: 20px; height: 20px; top: 5px; position: relative;"></span>
		      <label class="black" style="font-size: 1.3rem; font-weight: 600;">Reject</label>
		  </div>
		</div>

		@include('layouts.styles.reportfooter-v2')
		@include('layouts.scripts.approve', ['table' => 'inspection_reports', 'id' => $treatingIron->report->id])
@endpush
