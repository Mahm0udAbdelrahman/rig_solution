{{-- Calibration Table Partial --}}
{{-- Parameters: direction, title, rowRange --}}

<table class="table" style="text-align: center;">
    <thead>
        <tr>
            <th colspan="7" class="text-center tb-title-background p-0 border-dark white border-diffrent">
                {{ $title }}
            </th>
        </tr>
        <tr>
            <th colspan="1" class="text-center tb-title-background p-0 border-dark white border-diffrent"></th>
            <th colspan="6" class="text-center tb-title-background p-0 border-dark white border-diffrent">
                No. of reading
            </th>
        </tr>
        <tr>
            <th class="tb-background text-center p-0 border-dark white border-diffrent">
                Target Value
            </th>
            <th class="tb-background text-center p-0 border-dark white border-diffrent">Target torque value</th>
            <th class="tb-background text-center p-0 border-dark white border-diffrent">Average reference torque</th>
            <th class="tb-background text-center p-0 border-dark white border-diffrent">Mean value of the measurement error</th>
            <th class="tb-background text-center p-0 border-dark white border-diffrent">Relative Expanded Uncertainty*</th>
            <th class="tb-background text-center p-0 border-dark white border-diffrent">Relative measurement uncertainty interval</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rowRange as $index)
            <tr>
                @foreach(['target_value1', 'target_value2', '1st', '2nd', '3rd', '4th', '5th'] as $inputName)
                    @if($inputName == 'target_value1')
                        <td class="text-center p-0 border-dark white">
                            @if($index == 1 || $index == 11)
                                <p class="text-black">20%</p>
                            @elseif($index == 2 || $index == 12)
                                <p class="text-black">60%</p>
                            @else
                                <p class="text-black">100%</p>
                            @endif
                        </td>
                    @elseif($inputName == 'target_value2')
                        <!-- <td class="text-center p-0 border-dark white">
                            @if($index == 1)
                                <p class="text-black">300</p>
                            @elseif($index == 2)
                                <p class="text-black">900</p>
                            @else
                                <p class="text-black">1500</p>
                            @endif
                        </td>    -->
                    @else
                        <td class="text-center p-0 border-dark white">
                            {{ Form::text("calibration_details[$direction][$index][$inputName]", null, [
                                'class' => 'form-control cell-input',
                                'placeholder' => '...'
                            ]) }}
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table> 