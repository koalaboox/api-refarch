@extends ('layouts.app')

@section ('content')
    <div class="container">
        <a href="{{ route('invoice.index') }}">&lt; All invoices</a>
        <h1>Invoice {{ $invoice->id }}</h1>

        @if ($invoice->number)
            <dl class="row">
                <dt class="col-sm-3">Document number</dt>
                <dd class="col-sm-9">{{ $invoice->number }}€</dd>
            </dl>
        @endif

        <div class="mt-2">
            <h2>Invoice lines</h2>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit price</th>
                    <th>Tax rate</th>
                </tr>
                @foreach ($invoice->lines as $line)
                    <tr>
                        <td>{{ $line->product->description }}</td>
                        <td>{{ $line->product->quantity }}</td>
                        <td>{{ $line->product->unitPrice }}</td>
                        <td>
                            @if (!empty($line->product->taxRate))
                                {{ $line->product->taxRate * 100 }}%
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        @if (!empty($invoice->totals->vatTotals))
            <h2 class="mt-2">Taxes</h2>

            <dl class="row">
                @foreach ($invoice->totals->vatTotals as $tax)
                    <dt class="col-sm-3">{{ $tax->label }} of {{ $tax->amountHT }}</dt>
                    <dd class="col-sm-9">{{ $tax->amountTVA }}€</dd>
                @endforeach
            </dl>
        @endif
        <h2 class="mt-2">Total</h2>
        <dl class="row">
            <dt class="col-sm-3">Total</dt>
            <dd class="col-sm-9">{{ $invoice->totals->total }}€</dd>
            @if ($invoice->totals->total != $invoice->totals->balance)
                <dt class="col-sm-3">Balance</dt>
                <dd class="col-sm-9">{{ $invoice->totals->balance }}€</dd>
            @endif
        </dl>
    </div>
@endsection