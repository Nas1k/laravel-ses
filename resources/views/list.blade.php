@extends('layouts.main')

@section('title', 'SES Reports')

@section('content')
    <link rel="import" href="https://cdn.vaadin.com/vaadin-core-elements/latest/vaadin-grid/vaadin-grid.html">
    <vaadin-grid id="report-list">
        <table>
            <colgroup>
                <col name="id">
                <col name="status">
                <col name="message">
            </colgroup>
        </table>
    </vaadin-grid>
    <script>
        document.querySelector('#report-list').items = {!! $items !!};
    </script>
@endsection
