<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
		<title>Advanced Accounting Reports</title>
		<style>
				body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
				h1, h2 { margin: 0 0 8px 0; }
				h1 { font-size: 20px; }
				h2 { font-size: 14px; margin-top: 16px; }
				.meta { margin-bottom: 8px; }
				.meta span { margin-right: 12px; }
				table { width: 100%; border-collapse: collapse; margin-top: 6px; }
				th, td { border: 1px solid #d7d7d7; padding: 5px 6px; }
				th { background: #f3f6ff; text-align: left; }
				td.num, th.num { text-align: right; }
				.summary { margin-top: 6px; }
		</style>
</head>
<body>
		<h1>Advanced Accounting Reports</h1>
		<div class="meta">
				<span><strong>Date From:</strong> {{ $date_from ?: 'All' }}</span>
				<span><strong>Date To:</strong> {{ $date_to ?: 'All' }}</span>
				<span><strong>Currency:</strong> {{ $report_currency ?: 'All' }}</span>
				@if(!empty($ledger_account_label))
						<span><strong>Ledger:</strong> {{ $ledger_account_label }}</span>
				@endif
		</div>

		<h2>Currency Totals</h2>
		<table>
				<thead>
				<tr>
						<th>Currency</th>
						<th class="num">Debit</th>
						<th class="num">Credit</th>
						<th class="num">Difference</th>
				</tr>
				</thead>
				<tbody>
				@forelse(($currency_totals ?? collect()) as $row)
						<tr>
								<td>{{ $row['currency'] }}</td>
								<td class="num">{{ number_format((float)$row['debit'], 2, '.', ',') }}</td>
								<td class="num">{{ number_format((float)$row['credit'], 2, '.', ',') }}</td>
								<td class="num">{{ number_format((float)$row['difference'], 2, '.', ',') }}</td>
						</tr>
				@empty
						<tr><td colspan="4">No data</td></tr>
				@endforelse
				</tbody>
		</table>

		<h2>Trial Balance</h2>
		<table>
				<thead>
				<tr>
						<th>Code</th>
						<th>Account</th>
						<th>Type</th>
						<th>Currency</th>
						<th class="num">Debit</th>
						<th class="num">Credit</th>
						<th class="num">Net</th>
				</tr>
				</thead>
				<tbody>
				@forelse($trial_balance_rows as $row)
						<tr>
								<td>{{ $row['code'] }}</td>
								<td>{{ $row['name'] }}</td>
								<td>{{ ucfirst($row['type']) }}</td>
								<td>{{ $row['currency'] ?? '-' }}</td>
								<td class="num">{{ number_format((float)$row['debit'], 2, '.', ',') }}</td>
								<td class="num">{{ number_format((float)$row['credit'], 2, '.', ',') }}</td>
								<td class="num">{{ number_format((float)$row['net'], 2, '.', ',') }}</td>
						</tr>
				@empty
						<tr><td colspan="7">No data</td></tr>
				@endforelse
				</tbody>
		</table>
		<div class="summary">
				<strong>Totals:</strong>
				Debit {{ number_format((float)($trial_balance_totals['debit'] ?? 0), 2, '.', ',') }} |
				Credit {{ number_format((float)($trial_balance_totals['credit'] ?? 0), 2, '.', ',') }} |
				Difference {{ number_format((float)($trial_balance_totals['difference'] ?? 0), 2, '.', ',') }}
		</div>

		<h2>Income Statement</h2>
		<table>
				<thead>
				<tr>
						<th>Code</th>
						<th>Account</th>
						<th>Type</th>
						<th>Currency</th>
						<th class="num">Amount</th>
				</tr>
				</thead>
				<tbody>
				@forelse($income_statement_rows as $row)
						<tr>
								<td>{{ $row['code'] }}</td>
								<td>{{ $row['name'] }}</td>
								<td>{{ ucfirst($row['type']) }}</td>
								<td>{{ $row['currency'] ?? '-' }}</td>
								<td class="num">{{ number_format((float)$row['normal_amount'], 2, '.', ',') }}</td>
						</tr>
				@empty
						<tr><td colspan="5">No data</td></tr>
				@endforelse
				</tbody>
		</table>
		<div class="summary">
				<strong>Revenue:</strong> {{ number_format((float)($income_statement_totals['revenue'] ?? 0), 2, '.', ',') }}
				| <strong>Expense:</strong> {{ number_format((float)($income_statement_totals['expense'] ?? 0), 2, '.', ',') }}
				| <strong>Net Income:</strong> {{ number_format((float)($income_statement_totals['net_income'] ?? 0), 2, '.', ',') }}
		</div>

		<h2>Balance Sheet</h2>
		<table>
				<thead>
				<tr>
						<th>Code</th>
						<th>Account</th>
						<th>Type</th>
						<th>Currency</th>
						<th class="num">Balance</th>
				</tr>
				</thead>
				<tbody>
				@forelse(($balance_sheet['rows'] ?? collect()) as $row)
						<tr>
								<td>{{ $row['code'] }}</td>
								<td>{{ $row['name'] }}</td>
								<td>{{ ucfirst($row['type']) }}</td>
								<td>{{ $row['currency'] ?? '-' }}</td>
								<td class="num">{{ number_format((float)$row['balance'], 2, '.', ',') }}</td>
						</tr>
				@empty
						<tr><td colspan="5">No data</td></tr>
				@endforelse
				</tbody>
		</table>
		<div class="summary">
				<strong>Assets:</strong> {{ number_format((float)($balance_sheet['assets_total'] ?? 0), 2, '.', ',') }}
				| <strong>Liabilities + Equity:</strong> {{ number_format((float)($balance_sheet['liabilities_and_equity_total'] ?? 0), 2, '.', ',') }}
				| <strong>Difference:</strong> {{ number_format((float)($balance_sheet['difference'] ?? 0), 2, '.', ',') }}
		</div>

		<h2>General Ledger</h2>
		<table>
				<thead>
				<tr>
						<th>Date</th>
						<th>Entry</th>
						<th>Reference</th>
						<th>Memo</th>
						<th>Side</th>
						<th>Currency</th>
						<th class="num">Debit</th>
						<th class="num">Credit</th>
						<th class="num">Running Balance</th>
				</tr>
				</thead>
				<tbody>
				@forelse($ledger_rows as $row)
						<tr>
								<td>{{ $row['posting_date'] }}</td>
								<td>{{ $row['entry_code'] }}</td>
								<td>{{ $row['reference_no'] }}</td>
								<td>{{ $row['memo'] }}</td>
								<td>{{ $row['side'] }}</td>
								<td>{{ $row['currency'] ?? '-' }}</td>
								<td class="num">{{ number_format((float)$row['debit'], 2, '.', ',') }}</td>
								<td class="num">{{ number_format((float)$row['credit'], 2, '.', ',') }}</td>
								<td class="num">{{ number_format((float)$row['running_balance'], 2, '.', ',') }}</td>
						</tr>
				@empty
						<tr><td colspan="9">No ledger rows for current filters.</td></tr>
				@endforelse
				</tbody>
		</table>
		<div class="summary">
				<strong>Totals:</strong>
				Debit {{ number_format((float)($ledger_totals['debit'] ?? 0), 2, '.', ',') }} |
				Credit {{ number_format((float)($ledger_totals['credit'] ?? 0), 2, '.', ',') }} |
				Balance {{ number_format((float)($ledger_totals['balance'] ?? 0), 2, '.', ',') }}
		</div>
</body>
</html>
