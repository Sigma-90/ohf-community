@extends('layouts.app')

@section('title', 'People')

@section('buttons')
    @can('create', App\Person::class)
        <a href="{{ route('people.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="d-none d-md-inline">  Register</span></a>
    @endcan
    <a href="{{ route('people.charts') }}" class="btn btn-secondary"><i class="fa fa-line-chart"></i><span class="d-none d-md-inline">  Charts</span></a>
    @can('list', App\Person::class)
        <a href="{{ route('people.export') }}" class="btn btn-secondary"><i class="fa fa-download"></i><span class="d-none d-md-inline">  Export</span></a>
    @endcan
    @can('create', App\Person::class)
        <a href="{{ route('people.import') }}" class="btn btn-secondary"><i class="fa fa-upload"></i><span class="d-none d-md-inline">  Import</span></a>
    @endcan
@endsection

@section('content')

    <table class="table table-sm table-striped table-bordered table-hover table-responsive-md" id="results-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Family Name</th>
                <th>Case No.</th>
                <th>Med No.</th>
                <th>Reg No.</th>
                <th>Sec Card No.</th>
                <th>Nationality</th>
                <th>Languages</th>
                <th>Skills</th>
                <th>Remarks</th>
            </tr>
            <tr id="filter">
                <th>{{ Form::text('name', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('family_name', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('case_no', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('medical_no', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('registration_no', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('section_card_no', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('nationality', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('languages', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('skills', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
                <th>{{ Form::text('remarks', null, [ 'class' => 'form-control form-control-sm', 'placeholder' => 'Filter...', 'autocomplete' => 'off' ]) }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10">Loading, please wait...</td>
            </tr>
        </tbody>
    </table>
    <div class="row">
        <div class="col-auto align-items-center">
            <ul class="pagination pagination-sm" id="paginator"></ul>
        </div>
        <div class="col align-items-center"><small id="paginator-info"></small></div>
    </div>

@endsection

@section('script')
    var delayTimer;

    $(function(){
        $('#filter input').on('change keyup', function(e){
            var keyCode = e.keyCode;
            if (keyCode == 0 || keyCode == 8 || keyCode == 27 || keyCode == 46 || (keyCode >= 48 && keyCode <= 90) || (keyCode >= 96 && keyCode <= 111)) {
                var elem = $(this);

                $('#filter-status').html('');
                var tbody = $('#results-table tbody');
                tbody.empty();
                tbody.append($('<tr>')
                    .append($('<td>')
                        .text('Searching...')
                        .attr('colspan', 10))
                    );

                clearTimeout(delayTimer);
                delayTimer = setTimeout(function(){
                    if (keyCode == 27) {  // ESC
                        elem.val('').focus();
                    }
                    filterTable(elem.val());
                }, 300);
            }
        });

        filterTable(1);
    });
    
    function filterTable(page) {
		$('#filter-status').html('');
        var tbody = $('#results-table tbody');
        tbody.empty();
        tbody.append($('<tr>')
            .append($('<td>')
                .text('Searching...')
                .attr('colspan', 10))
        );

        var pagination = $('#paginator');
        pagination.empty();

        var paginationInfo = $( '#paginator-info' );
        paginationInfo.empty();

        $.post( "{{ route('people.filter') }}", {
            "_token": "{{ csrf_token() }}",
            "name": $('#filter input[name="name"]').val(),
            "family_name": $('#filter input[name="family_name"]').val(),
            "case_no": $('#filter input[name="case_no"]').val(),
            "medical_no": $('#filter input[name="medical_no"]').val(),
            "registration_no": $('#filter input[name="registration_no"]').val(),
            "section_card_no": $('#filter input[name="section_card_no"]').val(),
            "nationality": $('#filter input[name="nationality"]').val(),
            "languages": $('#filter input[name="languages"]').val(),
            "skills": $('#filter input[name="skills"]').val(),
            "remarks": $('#filter input[name="remarks"]').val(),
            "page": page
        }, function(result) {
            tbody.empty();
            if (result.data.length > 0) {
                $.each(result.data, function(k, v){
                    tbody.append(writeRow(v));
                });
                updatePagination(pagination, result);
                paginationInfo.html( result.from + ' - ' + result.to + ' of ' + result.total );
            } else {
                tbody.append($('<tr>')
                    .addClass('warning')
                    .append($('<td>')
                        .text('No results')
                        .attr('colspan', 10))
                );
            }
        })
        .fail(function(jqXHR, textStatus) {
            tbody.empty();
            tbody.append($('<tr>')
                .addClass('danger')
                .append($('<td>')
                    .text(textStatus)
                    .attr('colspan', 10))
            );
        });
    }

    function updatePagination(container, result) {
        // First page
        if (result.current_page > 1) {
            container.append( createPaginationItem( '&laquo;', 1, null ) );
        } else {
            container.append( createPaginationItem( '&laquo;', null, 'disabled' ) );
        }

        // Previous page
        if (result.current_page > 1) {
            container.append( createPaginationItem( '&lsaquo;', result.current_page - 1, null ) );
        } else {
            container.append( createPaginationItem( '&lsaquo;', null, 'disabled' ) );
        }

        // Pages before
        for (i = 2; i >= 1; i--) {
            if (result.current_page > i) {
                container.append( createPaginationItem( result.current_page - i, result.current_page - i, null ) );
            }
        }

        // Current page
        container.append( createPaginationItem( result.current_page, null, 'active' ) );

        // PAges after
        for (i = 1; i <= 2; i++) {
            if ( result.current_page  + i - 1 < result.last_page ) {
                container.append( createPaginationItem( result.current_page + i, result.current_page + i, null ) );
            }
        }

        // Next page
        if (result.current_page < result.last_page) {
            container.append( createPaginationItem( '&rsaquo;', result.current_page + 1, null ) );
        } else {
            container.append( createPaginationItem( '&rsaquo;', null, 'disabled' ) );
        }

        // Last page
        if (result.current_page < result.last_page) {
            container.append( createPaginationItem( '&raquo;', result.last_page, null ) );
        } else {
            container.append( createPaginationItem( '&raquo;', null, 'disabled' ) );
        }
    }

    function createPaginationItem( content, pageTarget, elemClass ) {
        var elem = $( '<li>' )
            .addClass( 'page-item' )
        if ( pageTarget != null ) {
            elem.append($( '<a>' )
                .addClass( 'page-link' )
                .attr('href', 'javascript:;')
                .html( content )
                .on('click', function(){
                    filterTable( pageTarget );
                })
            )
        } else {
            elem.append($( '<span>' )
                .addClass( 'page-link' )
                .html( content )
            )
        }
        if ( elemClass != null ) {
            elem.addClass( elemClass )
        }
        return elem;
    }

    function writeRow(person) {
        return $('<tr>')
            .attr('id', 'person-' + person.id)
            .append($('<td>')
                .append($('<a>')
                    .attr('href', 'people/' + person.id)
                    .text(person.name)
                )            
            )
            .append($('<td>')
                .append($('<a>')
                    .attr('href', 'people/' + person.id)
                    .text(person.family_name)
                )
            )
            .append($('<td>').text(person.case_no))
            .append($('<td>').text(person.medical_no))
            .append($('<td>').text(person.registration_no))
            .append($('<td>').text(person.section_card_no))
            .append($('<td>').text(person.nationality))
            .append($('<td>').text(person.languages))
            .append($('<td>').text(person.skills))
            .append($('<td>').text(person.remarks));
    }
@endsection
