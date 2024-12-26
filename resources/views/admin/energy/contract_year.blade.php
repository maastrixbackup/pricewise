<table class="table table-responsive">
    <thead>
        <tr>
            <th>Contract Year</th>
            <th>Power Per Unit (€)</th>
            <th>Gas Per Unit (€)</th>
            <th>Discount(%)</th>
            <th>Valid Till</th>
            <th>Boiler</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($bArr))
            @foreach (range(1, 5) as $year)
                <tr>
                    <td>
                        @if (is_array($bArr['contract_length']) && array_key_exists($year, $bArr['contract_length']))
                            <input type="text" class="form-control" readonly placeholder="Contract Year"
                                value="{{ $bArr['contract_length'][$year] }}">
                            <input type="checkbox" style="display: none;" name="contract_year[]" checked
                                id="year_{{ $year }}" value="{{ $year }}">
                            <input type="hidden" class="form-control" placeholder="Id" name="ids[{{ $year }}]"
                                value="{{ $bArr['id'][$year] }}">
                        @else
                            <label for="year_{{ $year }}">
                                <input type="checkbox" name="contract_year[]" id="year_{{ $year }}"
                                    value="{{ $year }}"> {{ $year }}
                            </label>
                        @endif
                    </td>
                    <td>
                        <input type="number" placeholder="Ex:-0.02"
                            class="form-control @if (!(is_array($bArr['power_cost_per_unit']) && array_key_exists($year, $bArr['power_cost_per_unit']))) disabled @endif"
                            name="year_power[{{ $year }}]" id="power_year_{{ $year }}"
                            value="{{ $bArr['power_cost_per_unit'][$year] ?? '' }}"
                            {{ !(is_array($bArr['power_cost_per_unit']) && array_key_exists($year, $bArr['power_cost_per_unit'])) ? 'disabled readonly' : '' }}>
                    </td>
                    <td>
                        <input type="number" placeholder="Ex:-0.02"
                            class="form-control @if (!(is_array($bArr['gas_cost_per_unit']) && array_key_exists($year, $bArr['gas_cost_per_unit']))) disabled @endif"
                            name="year_gas[{{ $year }}]" id="gas_year_{{ $year }}"
                            value="{{ $bArr['gas_cost_per_unit'][$year] ?? '' }}"
                            {{ !(is_array($bArr['gas_cost_per_unit']) && array_key_exists($year, $bArr['gas_cost_per_unit'])) ? 'disabled readonly' : '' }}>
                    </td>
                    <td>
                        <input type="number" placeholder="Ex:-21"
                            class="form-control @if (!(is_array($bArr['discount']) && array_key_exists($year, $bArr['discount']))) disabled @endif"
                            name="discount[{{ $year }}]" id="discount_year_{{ $year }}"
                            value="{{ $bArr['discount'][$year] ?? '' }}"
                            {{ !(is_array($bArr['discount']) && array_key_exists($year, $bArr['discount'])) ? 'disabled readonly' : '' }}>
                    </td>
                    <td>
                        <input type="date" class="form-control @if (!(is_array($bArr['valid_till']) && array_key_exists($year, $bArr['valid_till']))) disabled @endif"
                            name="valid_till[{{ $year }}]" id="valid_till_{{ $year }}"
                            value="{{ $bArr['valid_till'][$year] ?? '' }}"
                            {{ !(is_array($bArr['valid_till']) && array_key_exists($year, $bArr['valid_till'])) ? 'disabled readonly' : '' }}>
                    </td>
                    <td>
                        <input type="number" class="form-control @if (!(is_array($bArr['boiler']) && array_key_exists($year, $bArr['boiler']))) disabled @endif"
                            name="boiler[{{ $year }}]" id="bolier_{{ $year }}"
                            value="{{ $bArr['boiler'][$year] ?? '' }}" placeholder="Boiler"
                            {{ !(is_array($bArr['boiler']) && array_key_exists($year, $bArr['boiler'])) ? 'disabled readonly' : '' }}>
                    </td>
                </tr>
            @endforeach
        @else
            @foreach (range(1, 5) as $year)
                <tr>
                    <td>
                        <label for="year_{{ $year }}">
                            <input type="checkbox" name="contract_year[]" id="year_{{ $year }}"
                                value="{{ $year }}"> {{ $year }}
                            <span id="doneData_{{ $year }}"></span>
                        </label>
                    </td>
                    <td><input type="number" disabled placeholder="Ex:-0.02" value="" readonly
                            class="form-control" name="year_power[{{ $year }}]"
                            id="power_year_{{ $year }}"></td>
                    <td><input type="number" disabled placeholder="Ex:-0.02" value="" readonly
                            class="form-control" name="year_gas[{{ $year }}]"
                            id="gas_year_{{ $year }}"></td>
                    <td><input type="number" disabled name="discount[{{ $year }}]"
                            id="discount_year_{{ $year }}" class="form-control" value="" readonly
                            placeholder="Ex:-21"></td>
                    <td><input type="date" disabled name="valid_till[{{ $year }}]"
                            id="valid_till_{{ $year }}" class="form-control" value="" readonly></td>
                    <td><input type="number" disabled name="boiler[{{ $year }}]"
                            id="boiler_{{ $year }}" class="form-control" value="" placeholder="Boiler"
                            readonly></td>
                </tr>
            @endforeach
        @endif

    </tbody>
</table>
