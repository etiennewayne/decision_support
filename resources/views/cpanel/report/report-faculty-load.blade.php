@extends('layouts.app')

@section('content')
    <report-faculty-load prop-acad-years='@json($acadYears)'>
    </report-faculty-load>
@endsection
