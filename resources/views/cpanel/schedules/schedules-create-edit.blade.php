@extends('layouts.app')

@section('content')
    @if (isset($data))
        <schedules-create-edit prop-rooms='@json($rooms)' prop-data='@json($data)'
            prop-acad-years='@json($acadYears)' prop-programs='@json($programs)'>
        </schedules-create-edit>
    @else
        <schedules-create-edit prop-rooms='@json($rooms)' prop-acad-years='@json($acadYears)'
            prop-programs='@json($programs)'>
        </schedules-create-edit>
    @endif
@endsection
