@extends('layouts.app')

@section('content')
    <schedules-create-edit prop-acad-years='@json($acadYears)' prop-programs='@json($programs)'>
    </schedules-create-edit>
@endsection
