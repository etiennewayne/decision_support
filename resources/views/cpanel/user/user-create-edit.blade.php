@extends('layouts.app')

@section('content')
    <user-create-edit prop-programs='@json($programs)' 
        prop-id="{{$id}}">
    </user-create-edit>
@endsection

