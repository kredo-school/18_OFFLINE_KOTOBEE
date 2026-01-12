(() => {
    if (!window.kana_game_time_attacks_avg_chart) return;

    const {
        chart_id,
        labels,
        hiragana_data,
        katakana_data
    } = window.kana_game_time_attacks_avg_chart;

    const canvas = document.getElementById(chart_id);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'hiragana', data: hiragana_data },
                { label: 'katakana', data: katakana_data },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // 追加(高さ変更のため)
            plugins: {
                title: {
                    display: true,
                    text: 'kana_game: 各ユーザのベストタイムの平均 (time_attack)'
                },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'sec'
                    }
                },
                x: {
                    title: {
                        display: true,
                    }
                }
            }
        }
    });
})();
