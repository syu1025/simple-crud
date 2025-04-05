<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カロリー記録アプリ</title>
    @yield('styles')  <!-- 子ビューからのスタイルを挿入 -->
</head>
<style>
    .calorie-sidebar {
        width: 100%;
        background-color: #f8f9fa;
        border-right: 1px solid #e9ecef;
        padding: 0.5rem;
        font-family: 'Segoe UI', sans-serif;
        height: 100%;
        overflow-y: auto;
    }

    .sidebar-title {
        font-size: 1.2rem;
        margin-bottom: 1rem;
        padding: 0.5rem;
        color: #343a40;
        border-bottom: 1px solid #dee2e6;
    }

    .sidebar-section {
        margin-bottom: 0.5rem;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-header {
        background-color: #2c3e50;
        color: white;
        padding: 0.6rem 0.8rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s ease;
        font-size: 0.9rem;
    }

    .sidebar-header:hover {
        background-color: #34495e;
    }

    .sidebar-header.active {
        background-color: #e67e22;
    }

    .sidebar-header h5 {
        margin: 0;
        font-size: 0.95rem;
    }

    .sidebar-header small {
        font-size: 0.75rem;
        opacity: 0.9;
    }

    .sidebar-content {
        background-color: #ffffff;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .sidebar-item {
        padding: 0.6rem 0.8rem;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s ease;
        font-size: 0.85rem;
    }

    .sidebar-item:last-child {
        border-bottom: none;
    }

    .sidebar-item:hover {
        background-color: #f9f9f9;
    }

    .sidebar-item h5 {
        margin: 0 0 0.3rem 0;
        font-size: 0.9rem;
        color: #343a40;
    }

    .sidebar-item-details {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .arrow {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
    }

    .sidebar-header.active .arrow {
        transform: rotate(180deg);
    }

    .container-sidebar {
        display: flex;  /* フレックスボックスを使用 */
        height: 100vh; /* ビューポートの高さに合わせる */
    }

    .sidebar {
        width: 250px;  /* サイドバーの幅 */
        background-color: #f8f9fa; /* サイドバーの背景色 */
        border-right: 1px solid #dee2e6; /* サイドバーの右境界線 */
    }
    .main-content{
        width: 100%;
    }
</style>
<body>
    <div class="container-sidebar">
        <div class="sidebar">
            <div class="calorie-sidebar">
                <a href="{{ route('records.index') }}">
                    <h4 class="sidebar-title">カロリー集計</h4>
                </a>
                @foreach ($per_years as $year_data)
                    <div class="sidebar-section">
                        <div class="sidebar-header">
                            <div>
                                <h5>{{ $year_data->year }}年</h5>
                                <small>摂取: {{ number_format($year_data->year_total_intake) }} / 消費: {{ number_format($year_data->year_total_burned) }}</small>
                            </div>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="sidebar-content">
                            @foreach ($per_months->where('year', $year_data->year) as $month_data)
                                <div class="sidebar-item">
                                    <a href="{{route('records.index') }}?year={{ $month_data->year }}&month={{ $month_data->month}}">
                                        <h5>{{ $month_data->month }}月</h5>
                                        <div class="sidebar-item-details">
                                            <div>摂取: {{ number_format($month_data->month_total_intake) }}</div>
                                            <div>消費: {{ number_format($month_data->month_total_burned) }}</div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.sidebar-header').forEach(header => {
                header.addEventListener('click', () => {
                    header.classList.toggle('active');
                    const content = header.nextElementSibling;
                    if (content.style.maxHeight) {
                        content.style.maxHeight = null;
                    } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                });
            });
        });
    </script>
</body>
</html>
