{{-- Text Backpack CRUD filter --}}

<li filter-name="{{ $filter->name }}"
    filter-type="{{ $filter->type }}"
    filter-key="{{ $filter->key }}"
    @if ($filter->currentValue)
		filter-value="{{ $filter->currentValue }}"
    @else
		style="display: none;"
        filter-value=""
	@endif
	class="nav-item dropdown app_filter {{ Request::get($filter->name) ? 'active' : '' }}">
	<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $filter->label }} <span class="caret"></span></a>
	<div class="dropdown-menu p-0">
		<div class="form-group backpack-filter mb-0">
            <?php
                $operator = 'like';
                $value = '';
                if ($filter->currentValue) {
                    $criteria = (array) json_decode($filter->currentValue);
                    $operator = isset($criteria['operator']) ? $criteria['operator'] : 'like_not';
                    $value = isset($criteria['value']) ? $criteria['value'] : '';
                }
            ?>
            <div class="p-2" id="operators">
                @foreach($filter->values as $key => $operator_value)
                    <div>
                        <input
                            type="radio"
                            class="operator"
                            name="{{$filter->name}}_operator"
                            id="{{$filter->name}}_{{$key}}"
                            value="{{ $key }}"
                            @if($key==$operator)
                                checked="true"
                            @endif
                        >
                        <label for="{{$filter->name}}_{{$key}}" style="min-width: 80%;">{{ $operator_value }}</label>
                    </div>
                @endforeach
				<div>
					<input type="radio" class="operator" name="{{$filter->name}}_operator" id="{{$filter->name}}_in" value="in">
					<label for="{{$filter->name}}_in" style="min-width: 80%;">Multiple</label>
				</div>
            </div>
            
			<div class="input-group">
		        <input class="form-control pull-right value"
		        		id="text-filter-{{ $filter->key }}"
		        		type="text"
						@if ($value)
							value="{{ $value }}"
						@endif
		        		>
		        <div class="input-group-append text-filter-{{ $filter->key }}-clear-button">
		          <a class="input-group-text" href=""><i class="la la-times"></i></a>
		        </div>
		    </div>
		</div>
	</div>
</li>

{{-- ########################################### --}}
{{-- Extra CSS and JS for this particular filter --}}


{{-- FILTERS EXTRA JS --}}
{{-- push things in the after_scripts section --}}

@push('crud_list_scripts')
	<!-- include select2 js-->
  <script>
		jQuery(document).ready(function($) {

			const operators = {
				'like': 'Contiene',
				'equals': 'Igual',
				'not_equals': 'Diferente',
				'is_null': 'IS NULL',
				'in': 'Multiple',
				'not_in': 'Multiple Diferente'
			}
			function isOperatorIsNull(operator) {
				return operator === 'is_null';
			}

            const filterVisibilityOptionSelector = '#filterVisibility .dropdown-item[filter-name={{ $filter->name }}]';
            const filteredText = '(Filtered)';

			// $('#text-filter-{{ $filter->key }}').on('change', function(e) {
			$('li[filter-key={{ $filter->key }}] input, li[filter-key={{ $filter->key }}] .value').on('change', function(e) {

                var operator = $("li[filter-key={{ $filter->key }}] input:radio[name={{ $filter->name }}_operator]:checked").val();
				var c_value = $("li[filter-key={{ $filter->key }}] .value").val();
                
				//if (operator || c_value) {
				if ((c_value !== '' && c_value !== undefined) || isOperatorIsNull(operator)) {
					var range = {
						'operator': operator,
						'value': c_value
					};
					var value = JSON.stringify(range);
				} else {
					//this change to empty string,because addOrUpdateUriParameter method just judgment string
					var value = '';
				}
				console.log(value);


				var parameter = '{{ $filter->name }}';
				//var value = $(this).val();

		    	// behaviour for ajax table
				var ajax_table = $('#crudTable').DataTable();
				var current_url = ajax_table.ajax.url();
				var new_url = addOrUpdateUriParameter(current_url, parameter, value);

				// replace the datatables ajax url with new_url and reload it
				new_url = normalizeAmpersand(new_url.toString());
				ajax_table.ajax.url(new_url).load((e) => {
					if ($('.sectionGroupGraph').css('display') == 'none'){
						console.log(0);
						optionGraph(0);
					}else{
						console.log(1);
						optionGraph(1);
					}
				});

				// add filter to URL
				crud.updateUrl(new_url);

				// mark this filter as active in the navbar-filters
				if (URI(new_url).hasQuery('{{ $filter->name }}', true)) {
					$('li[filter-key={{ $filter->key }}]').removeClass('active').addClass('active');
                    $(filterVisibilityOptionSelector).text(`{{ $filter->label }} ${filteredText}`);
				} else {
					$('li[filter-key={{ $filter->key }}]').trigger('filter:clear');
                    $(filterVisibilityOptionSelector).text('{{ $filter->label }}');
				}
                //EXTRA JS
				$('li[filter-key={{ $filter->key }}]').attr('filter-value', value);
				
			});

			$('li[filter-key={{ $filter->key }}]').on('filter:clear', function(e) {
				$('li[filter-key={{ $filter->key }}]').removeClass('active');
				$('#text-filter-{{ $filter->key }}').val('');

                $(filterVisibilityOptionSelector).text('{{ $filter->label }}');
                //EXTRA JS
				$('li[filter-key={{ $filter->key }}]').attr('filter-value', '');
			});

			// datepicker clear button
			$(".text-filter-{{ $filter->key }}-clear-button").click(function(e) {
				e.preventDefault();

				$('li[filter-key={{ $filter->key }}]').trigger('filter:clear');
				$('#text-filter-{{ $filter->key }}').val('');
				$("li[filter-key={{ $filter->key }}] input:radio[name={{ $filter->name }}_operator][value='like']").prop('checked', true);
				$('#text-filter-{{ $filter->key }}').trigger('change');

                $(filterVisibilityOptionSelector).text('{{ $filter->label }}');
				$('li[filter-key={{ $filter->key }}]').attr('filter-value', '');
			})

            $('.dropdown-menu').on('click', function (e) {
                e.stopPropagation();
            });
		});
  </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
