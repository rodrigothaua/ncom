jQuery(document).ready(function($) {
    $.get('/processos-chart-data', function(data) {
        var ctx = document.getElementById('processosChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Verde', 'Laranja', 'Amarelo', 'Vermelho'],
                datasets: [{
                    data: [data.verde, data.laranja, data.amarelo, data.vermelho],
                    backgroundColor: ['green', 'orange', 'yellow', 'red'],
                }]
            }
        });
    });
});
