export function initSurveyCharts(ccLabels, ccData, suggestionsLabels, suggestionsData, sqLabels, sqData) {
    // Citizen's Charter Doughnut
    const ccCtx = document.getElementById('ccAwarenessChart').getContext('2d');
    new Chart(ccCtx, {
        type: 'doughnut',
        data: {
            labels: ccLabels,
            datasets: [{
                data: ccData,
                backgroundColor: ['#FFEA01', '#010767', '#CCCCCC']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Suggestions Bar
    const suggestionsCtx = document.getElementById('suggestionsChart').getContext('2d');
    new Chart(suggestionsCtx, {
        type: 'bar',
        data: {
            labels: suggestionsLabels,
            datasets: [{
                label: 'Suggestions',
                data: suggestionsData,
                backgroundColor: ['#FFEA01', '#010767']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Service Quality Line
    const sqCtx = document.getElementById('sqDimensionsChart').getContext('2d');
    new Chart(sqCtx, {
        type: 'line',
        data: {
            labels: sqLabels,
            datasets: [{
                label: 'Service Quality Responses',
                data: sqData,
                borderColor: '#010767',
                backgroundColor: 'rgba(1,7,103,0.2)',
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

window.initSurveyCharts = initSurveyCharts;