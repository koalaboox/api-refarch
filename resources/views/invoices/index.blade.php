@extends ('layouts.app')

@section ('content')
    <div class="container">
        <h1>All invoices</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Number</th>
                <th>Status</th>
                <th>Customer</th>
                <th>Due date</th>
                <th>Balance</th>
            </tr>
            </thead>
            @foreach ($invoices as $invoice)
                <tr>
                    <td>
                        <a href="{{ route('invoice.show', $invoice->id) }}">{{ $invoice->id }}</a>
                    </td>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->status }}</td>
                    <td>{{ !empty($invoice->customer) ? $invoice->customer->name1 : '' }}</td>
                    <td>{{ $invoice->meta->dueDate }}</td>
                    <td>{{ $invoice->totals->balance }} €</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection