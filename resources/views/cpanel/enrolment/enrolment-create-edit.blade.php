@extends('layouts.app')

@section('content')
    @if (isset($data))
        <enrolment-create-edit 
            prop-data='@json($data)'
            prop-acad-years='@json($acadYears)' 
            prop-programs='@json($programs)'>
        </enrolment-create-edit>
    @else
        <enrolment-create-edit
             prop-acad-years='@json($acadYears)'
            prop-programs='@json($programs)'>
        </enrolment-create-edit>
    @endif
@endsection
