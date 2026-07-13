<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
		<title>File Manager Export</title>
		<style>
				body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
				h1 { margin: 0 0 8px 0; font-size: 18px; }
				table { width: 100%; border-collapse: collapse; margin-top: 8px; }
				th, td { border: 1px solid #ddd; padding: 4px 5px; }
				th { background: #f3f6ff; text-align: left; }
		</style>
</head>
<body>
		<h1>File Manager Export</h1>
		<p>
				<strong>Module:</strong> {{ $filters['module'] ?? 'All' }} |
				<strong>Category:</strong> {{ $filters['category'] ?? 'All' }} |
				<strong>Extension:</strong> {{ $filters['extension'] ?? 'All' }} |
				<strong>JCF:</strong> {{ $filters['job_request_code'] ?? 'All' }} |
				<strong>Inspection Only:</strong> {{ (int)($filters['inspection_only'] ?? 0) === 1 ? 'Yes' : 'No' }}
		</p>
		<table>
				<thead>
				<tr>
						<th>File</th>
						<th>Path</th>
						<th>Module</th>
						<th>Category</th>
						<th>Entity Type</th>
						<th>Entity Code</th>
						<th>JCF Code</th>
						<th>Ext</th>
						<th>Size</th>
						<th>Generated At</th>
						<th>Inspection</th>
						<th>Available</th>
				</tr>
				</thead>
				<tbody>
				@forelse($rows as $row)
						<tr>
								<td>{{ $row->filename }}</td>
								<td>{{ $row->path }}</td>
								<td>{{ $row->module }}</td>
								<td>{{ $row->category }}</td>
								<td>{{ $row->entity_type }}</td>
								<td>{{ $row->entity_code }}</td>
								<td>{{ $row->job_request_code }}</td>
								<td>{{ $row->extension }}</td>
								<td>{{ $row->size_bytes }}</td>
								<td>{{ $row->generated_at }}</td>
								<td>{{ (int)$row->is_inspection === 1 ? 'Yes' : 'No' }}</td>
								<td>{{ (int)$row->is_available === 1 ? 'Yes' : 'No' }}</td>
						</tr>
				@empty
						<tr><td colspan="12">No files found.</td></tr>
				@endforelse
				</tbody>
		</table>
</body>
</html>

