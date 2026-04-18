<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>CourtVision - Players - {{ $team->name }}</title>
        <style>
            @page {
                margin: 24px 28px;
            }

            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
                color: #111827;
            }

            .header {
                background: #0b0d12;
                border: 1px solid rgba(251, 191, 36, 0.35);
                border-bottom: 3px solid #fbbf24;
                border-radius: 10px;
                padding: 14px 16px;
                margin-bottom: 14px;
                text-align: center;
            }

            .brand-name {
                font-size: 22px;
                font-weight: 800;
                margin: 0;
                line-height: 1;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            .brand-court {
                color: #fbbf24;
            }

            .brand-vision {
                color: #ffffff;
            }

            .brand-sub {
                font-size: 11px;
                color: #e5e7eb;
                margin-top: 2px;
            }

            .doc-title {
                font-size: 14px;
                font-weight: 700;
                margin: 0;
                color: #ffffff;
                margin-top: 8px;
            }

            .doc-meta {
                margin-top: 4px;
                font-size: 10px;
                color: #d1d5db;
                line-height: 1.35;
            }

            .doc-meta strong {
                color: #ffffff;
                font-weight: 700;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid #e5e7eb;
            }

            th,
            td {
                padding: 9px 8px;
                border-bottom: 1px solid #e5e7eb;
                text-align: left;
                vertical-align: top;
            }

            th {
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                color: #0b0d12;
                background: #fbbf24;
            }

            tbody tr:nth-child(even) {
                background: #f9fafb;
            }

            .col-jersey {
                width: 72px;
                text-align: center;
            }

            .col-position {
                width: 90px;
                text-align: center;
            }

            .empty {
                padding: 12px 6px;
                color: #6b7280;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="brand-name"><span class="brand-court">COURT</span><span class="brand-vision">VISION</span></div>
            <div class="brand-sub">SBA</div>

            <div class="doc-title">Players by Team</div>
            <div class="doc-meta">
                Team: <strong>{{ $team->name }}</strong>
                @if ($team->division)
                    &nbsp;|&nbsp; Division: <strong>{{ $team->division->name }}</strong>
                @endif
            </div>
            <div class="doc-meta">
                Total Players: <strong>{{ $players->count() }}</strong>
                &nbsp;|&nbsp; Generated: <strong>{{ $generatedAt->format('M d, Y h:i A') }}</strong>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-jersey">Jersey</th>
                    <th>Name</th>
                    <th class="col-position">Position</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($players as $index => $player)
                    <tr>
                        <td class="col-jersey">{{ $player->jersey_number ?: '-' }}</td>
                        <td>{{ $player->name }}</td>
                        <td class="col-position">{{ $player->position ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty">No players found for this team.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
