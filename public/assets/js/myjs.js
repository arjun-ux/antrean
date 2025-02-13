var colors = {
    primary        : "#6571ff",
    secondary      : "#7987a1",
    success        : "#05a34a",
    info           : "#66d1d1",
    warning        : "#fbbc06",
    danger         : "#ff3366",
    light          : "#e9ecef",
    dark           : "#060c17",
    muted          : "#7987a1",
    gridBorder     : "rgba(77, 138, 240, .15)",
    bodyColor      : "#000",
    cardBg         : "#fff"
}
var fontFamily = "'Roboto', Helvetica, sans-serif"

function grafikPasienPerPKM(data) {
    var categories = [];
    var series = [];

    // Looping data dengan parameter yang benar
    data.forEach(function(item) {
        categories.push(item.namafaskes); // Nama faskes
        series.push(item.jumlah_pasien_onsite); // Jumlah pasien
    });

    if ($('#grafikPasienPerPKM').length) {
        var options = {
            chart: {
                type: 'bar',
                height: '4000',
                parentHeightOffset: 0,
                foreColor: colors.bodyColor,
                background: colors.cardBg,
                toolbar: {
                    show: false
                },
            },
            theme: {
                mode: 'light'
            },
            tooltip: {
                theme: 'light'
            },
            colors: [colors.primary],
            grid: {
                padding: {
                    bottom: -4
                },
                borderColor: colors.gridBorder,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            series: [{
                name: 'Pasien Onsite',
                data: series
            }],
            xaxis: {
                type: 'category', // Perbaikan di sini, gunakan `category` untuk teks
                categories: categories, // Perbaikan di sini, cukup `categories`
                axisBorder: {
                    color: colors.gridBorder,
                },
                axisTicks: {
                    color: colors.gridBorder,
                },
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: 'center',
                fontFamily: fontFamily,
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                },
            },
            stroke: {
                width: 0
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4
                }
            }
        };

        var apexBarChart = new ApexCharts(document.querySelector("#grafikPasienPerPKM"), options);
        apexBarChart.render();
    }
}

function grafikPasienOnPkm(data){
    var categories = [];
    var series = [];

    // Looping data dengan parameter yang benar
    data.forEach(function(item) {
        categories.push(item.poli); // Nama faskes
        series.push(item.jumlah_poli); // Jumlah pasien
    });

    if ($('#grafikPasienPKM').length) {
        var options = {
            chart: {
                type: 'bar',
                height: '350',
                parentHeightOffset: 0,
                foreColor: colors.bodyColor,
                background: colors.cardBg,
                toolbar: {
                    show: false
                },
            },
            theme: {
                mode: 'light'
            },
            tooltip: {
                theme: 'light'
            },
            colors: [colors.primary],
            grid: {
                padding: {
                    bottom: -4
                },
                borderColor: colors.gridBorder,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            series: [{
                name: 'Jumlah',
                data: series
            }],
            xaxis: {
                type: 'category', // Perbaikan di sini, gunakan `category` untuk teks
                categories: categories, // Perbaikan di sini, cukup `categories`
                axisBorder: {
                    color: colors.gridBorder,
                },
                axisTicks: {
                    color: colors.gridBorder,
                },
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: 'center',
                fontFamily: fontFamily,
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                },
            },
            stroke: {
                width: 0
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4
                }
            }
        };

        var apexBarChart = new ApexCharts(document.querySelector("#grafikPasienPKM"), options);
        apexBarChart.render();
    }
}

