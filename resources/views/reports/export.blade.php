<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $reportMeta['title'] }} - {{ $tenant->name ?? 'CyberStay' }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #1c1917;
            background: #fffaf5;
            font-size: 12px;
            line-height: 1.45;
        }

        .page {
            padding: 28px 30px 34px;
        }

        .hero {
            border: 1px solid #eadfce;
            border-radius: 22px;
            background: linear-gradient(135deg, #fff7ef 0%, #fff 48%, #f8ede4 100%);
            padding: 22px 24px;
            margin-bottom: 22px;
        }

        .hero table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-mark {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #9f1239;
            color: #fff;
            text-align: center;
            font-weight: 700;
            font-size: 20px;
            line-height: 48px;
            display: inline-block;
        }

        .eyebrow {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #9f1239;
            font-weight: 700;
        }

        .title {
            margin: 8px 0 0;
            font-size: 24px;
            font-weight: 700;
        }

        .subtitle {
            margin: 8px 0 0;
            color: #6f675e;
            font-size: 12px;
        }

        .meta {
            text-align: right;
            font-size: 11px;
            color: #6f675e;
        }

        .meta strong {
            display: block;
            color: #1c1917;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .stats {
            margin: 0 0 22px;
        }

        .stats td {
            width: 25%;
            padding-right: 10px;
            vertical-align: top;
        }

        .stat {
            border: 1px solid #eadfce;
            border-radius: 16px;
            background: #fff;
            padding: 14px;
            min-height: 86px;
        }

        .stat-label {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #8a8178;
            font-weight: 700;
        }

        .stat-value {
            margin-top: 10px;
            font-size: 24px;
            font-weight: 700;
        }

        .stat-copy {
            margin-top: 6px;
            font-size: 11px;
            color: #6f675e;
        }

        .section {
            margin-top: 20px;
            border: 1px solid #eadfce;
            border-radius: 18px;
            background: #fff;
            overflow: hidden;
        }

        .section-head {
            padding: 14px 18px;
            border-bottom: 1px solid #f0e7dc;
            background: #fff9f3;
        }

        .section-head h2 {
            margin: 0;
            font-size: 16px;
        }

        .section-head p {
            margin: 4px 0 0;
            color: #6f675e;
            font-size: 11px;
        }

        .section-body {
            padding: 16px 18px 18px;
        }

        .mini-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .mini-grid td {
            width: 25%;
            padding-right: 10px;
            vertical-align: top;
        }

        .mini-card {
            border: 1px solid #efe6db;
            border-radius: 14px;
            background: #fcfaf7;
            padding: 12px;
            min-height: 74px;
        }

        .mini-label {
            font-size: 10px;
            color: #8a8178;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
        }

        .mini-value {
            margin-top: 8px;
            font-size: 18px;
            font-weight: 700;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #8a8178;
            border-bottom: 1px solid #e8ddcf;
            background: #fcfaf7;
        }

        table.data td {
            padding: 10px 8px;
            vertical-align: top;
            border-bottom: 1px solid #f3eadf;
        }

        .num {
            font-variant-numeric: tabular-nums;
            font-weight: 700;
        }

        .text-positive {
            color: #047857;
        }

        .text-negative {
            color: #b91c1c;
        }

        .text-muted {
            color: #6f675e;
        }

        .footer {
            margin-top: 16px;
            font-size: 10px;
            color: #8a8178;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="hero">
            <table>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="width: 64px; vertical-align: top;"><span class="brand-mark">H</span></td>
                                <td style="vertical-align: top;">
                                    <div class="eyebrow">CyberStay Report</div>
                                    <div class="title">{{ $reportMeta['title'] }}</div>
                                    <div class="subtitle">{{ $reportMeta['subtitle'] }}</div>
                                    <div class="subtitle" style="margin-top: 10px; color: #1c1917;">
                                        {{ $tenant->name ?? 'CyberStay' }}
                                        @if($tenant?->city)
                                            · {{ $tenant->city }}
                                        @endif
                                        @if($tenant?->phone)
                                            · {{ $tenant->phone }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="meta" style="width: 225px; vertical-align: top;">
                        <strong>{{ $rangeLabel }}</strong>
                        Generated {{ $generatedAt->format('d M Y h:i A') }}<br>
                        @if($tenant?->email)
                            {{ $tenant->email }}<br>
                        @endif
                        @if($tenant?->address)
                            {{ $tenant->address }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        @if($reportKey === 'dashboard')
            <table class="stats">
                <tr>
                    <td>
                        <div class="stat">
                            <div class="stat-label">Cash collected</div>
                            <div class="stat-value">@money($cashCollected)</div>
                            <div class="stat-copy">Receipts captured from guest settlements.</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat">
                            <div class="stat-label">Gross billed</div>
                            <div class="stat-value">@money($grossRevenue)</div>
                            <div class="stat-copy">Room charges and extras billed.</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat">
                            <div class="stat-label">Total expenses</div>
                            <div class="stat-value">@money($expenseTotal)</div>
                            <div class="stat-copy">Operating spend plus payroll expense.</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat">
                            <div class="stat-label">Net profit</div>
                            <div class="stat-value {{ $profit >= 0 ? 'text-positive' : 'text-negative' }}">@money($profit)</div>
                            <div class="stat-copy">Collections less total expenses.</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="section">
                <div class="section-head">
                    <h2>Executive pulse</h2>
                    <p>Demand, occupancy, receivables, and payroll pressure in one view.</p>
                </div>
                <div class="section-body">
                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Bookings created</div><div class="mini-value">{{ $bookingsCreated }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Checked out</div><div class="mini-value">{{ $checkedOutCount }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Open balance</div><div class="mini-value">@money($balanceDue)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Payroll due</div><div class="mini-value">@money($payrollDue)</div></div></td>
                        </tr>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                                <th>Collections</th>
                                <th>Expenses</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyTrend as $row)
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td>{{ $row['bookings'] }}</td>
                                    <td class="num">@money($row['revenue'])</td>
                                    <td class="num">@money($row['collections'])</td>
                                    <td class="num">@money($row['expenses'])</td>
                                    <td class="num {{ $row['profit'] >= 0 ? 'text-positive' : 'text-negative' }}">@money($row['profit'])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($reportKey === 'bookings')
            <div class="section">
                <div class="section-head">
                    <h2>Booking performance</h2>
                    <p>Guest stays, settlements, and receivables for the selected range.</p>
                </div>
                <div class="section-body">
                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Bookings</div><div class="mini-value">{{ $bookingSummary['stays'] }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Checked out</div><div class="mini-value">{{ $bookingSummary['checked_out'] }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Guest nights</div><div class="mini-value">{{ $bookingSummary['guest_nights'] }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Average ticket</div><div class="mini-value">@money($bookingSummary['average_ticket'])</div></div></td>
                        </tr>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>Stay</th>
                                <th>Guests</th>
                                <th>Billed</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookingRows as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row['guest'] }}</strong><br>
                                        <span class="text-muted">{{ $row['reference'] }} · Room {{ $row['room'] }}</span>
                                    </td>
                                    <td>
                                        {{ $row['check_in'] }} to {{ $row['check_out'] }}<br>
                                        <span class="text-muted">{{ $row['nights'] }} night(s)</span><br>
                                        <span class="text-muted">In: {{ $row['checked_in_at'] }}</span><br>
                                        <span class="text-muted">Out: {{ $row['checked_out_at'] }}</span>
                                    </td>
                                    <td>{{ $row['guests'] }}</td>
                                    <td class="num">@money($row['billed'])</td>
                                    <td class="num">@money($row['paid'])</td>
                                    <td class="num">@money($row['due'])</td>
                                    <td>{{ $row['status'] }}<br><span class="text-muted">{{ $row['payment'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($reportKey === 'expenses')
            <div class="section">
                <div class="section-head">
                    <h2>Operating expenses</h2>
                    <p>Operating spend outside payroll, grouped by category and transaction.</p>
                </div>
                <div class="section-body">
                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Transactions</div><div class="mini-value">{{ $expenseSummary['transactions'] }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Operating total</div><div class="mini-value">@money($expenseSummary['operating_total'])</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Average ticket</div><div class="mini-value">@money($expenseSummary['average_ticket'])</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Top category</div><div class="mini-value">{{ $expenseSummary['top_category'] }}</div></div></td>
                        </tr>
                    </table>

                    <table class="data" style="margin-bottom: 14px;">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Transactions</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenseByCategory as $row)
                                <tr>
                                    <td>{{ ucfirst($row['category']) }}</td>
                                    <td>{{ $row['total'] }}</td>
                                    <td class="num">@money($row['amount'])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th>Expense</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Recorded by</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenseRows as $row)
                                <tr>
                                    <td><strong>{{ $row['title'] }}</strong><br><span class="text-muted">{{ $row['reference'] }} · {{ $row['notes'] }}</span></td>
                                    <td>{{ $row['date'] }}</td>
                                    <td>{{ $row['category'] }}</td>
                                    <td>{{ $row['recorded_by'] }}</td>
                                    <td class="num">@money($row['amount'])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($reportKey === 'salaries')
            <div class="section">
                <div class="section-head">
                    <h2>Payroll coverage</h2>
                    <p>Employee salary coverage and payment activity for the reporting window.</p>
                </div>
                <div class="section-body">
                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Active employees</div><div class="mini-value">{{ $salarySummary['active'] }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Salary paid</div><div class="mini-value">@money($salarySummary['paid'])</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Salary due</div><div class="mini-value">@money($salarySummary['due'])</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Payment cycles</div><div class="mini-value">{{ $salarySummary['cycles'] }}</div></div></td>
                        </tr>
                    </table>

                    <table class="data" style="margin-bottom: 14px;">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Status</th>
                                <th>Monthly salary</th>
                                <th>Paid in range</th>
                                <th>Cycles</th>
                                <th>Last paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryRows as $row)
                                <tr>
                                    <td><strong>{{ $row['employee'] }}</strong><br><span class="text-muted">{{ $row['position'] }}</span></td>
                                    <td>{{ $row['status'] }}</td>
                                    <td class="num">@money($row['monthly_salary'])</td>
                                    <td class="num">@money($row['paid_total'])</td>
                                    <td>{{ $row['cycles'] }}</td>
                                    <td>{{ $row['last_paid_on'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Month</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryPaymentRows as $row)
                                <tr>
                                    <td><strong>{{ $row['reference'] }}</strong><br><span class="text-muted">{{ $row['notes'] }}</span></td>
                                    <td>{{ $row['paid_on'] }}</td>
                                    <td>{{ $row['employee'] }}</td>
                                    <td>{{ $row['for_month'] }}</td>
                                    <td class="num">@money($row['amount'])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($reportKey === 'profit-loss')
            <div class="section">
                <div class="section-head">
                    <h2>Profit and loss</h2>
                    <p>Revenue, collections, receivables, payroll, and total spend with a monthly trend.</p>
                </div>
                <div class="section-body">
                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Room revenue</div><div class="mini-value">@money($roomRevenue)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Extras revenue</div><div class="mini-value">@money($extrasRevenue)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Operating expense</div><div class="mini-value">@money($operatingExpenseTotal)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Payroll expense</div><div class="mini-value">@money($salaryExpenseTotal)</div></div></td>
                        </tr>
                    </table>

                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Gross billed</div><div class="mini-value">@money($grossRevenue)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Cash collected</div><div class="mini-value">@money($cashCollected)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Receivables</div><div class="mini-value">@money($balanceDue)</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Net profit</div><div class="mini-value {{ $profit >= 0 ? 'text-positive' : 'text-negative' }}">@money($profit)</div></div></td>
                        </tr>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                                <th>Collections</th>
                                <th>Expenses</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyTrend as $row)
                                <tr>
                                    <td>{{ $row['label'] }}</td>
                                    <td>{{ $row['bookings'] }}</td>
                                    <td class="num">@money($row['revenue'])</td>
                                    <td class="num">@money($row['collections'])</td>
                                    <td class="num">@money($row['expenses'])</td>
                                    <td class="num {{ $row['profit'] >= 0 ? 'text-positive' : 'text-negative' }}">@money($row['profit'])</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($reportKey === 'audit')
            <div class="section">
                <div class="section-head">
                    <h2>Audit trail</h2>
                    <p>Chronological record of booking, settlement, expense, and payroll activity.</p>
                </div>
                <div class="section-body">
                    <table class="mini-grid">
                        <tr>
                            <td><div class="mini-card"><div class="mini-label">Events</div><div class="mini-value">{{ $auditSummary['events'] }}</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Collections</div><div class="mini-value">@money($auditSummary['collections'])</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Disbursements</div><div class="mini-value">@money($auditSummary['disbursements'])</div></div></td>
                            <td><div class="mini-card"><div class="mini-label">Open balance</div><div class="mini-value">@money($auditSummary['open_balance'])</div></div></td>
                        </tr>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th>When</th>
                                <th>Event</th>
                                <th>Reference</th>
                                <th>Actor</th>
                                <th>Detail</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($auditTrail as $entry)
                                <tr>
                                    <td>{{ $entry['occurred_at']?->format('d M Y h:i A') ?? '-' }}</td>
                                    <td><strong>{{ $entry['label'] }}</strong><br><span class="text-muted">{{ $entry['subject'] }}</span></td>
                                    <td>{{ $entry['reference'] }}</td>
                                    <td>{{ $entry['actor'] }}</td>
                                    <td>{{ $entry['detail'] }}</td>
                                    <td class="num {{ $entry['direction'] === 'positive' ? 'text-positive' : ($entry['direction'] === 'negative' ? 'text-negative' : '') }}">
                                        {{ $entry['direction'] === 'negative' ? '-' : ($entry['direction'] === 'positive' ? '+' : '') }}@money($entry['amount'])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="footer">
            Generated by CyberStay for {{ $tenant->name ?? 'CyberStay' }}
        </div>
    </div>
</body>
</html>
