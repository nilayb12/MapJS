const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, { trigger: 'hover' }));

function roundNum(num, precision) {
    return Math.round(num * Math.pow(10, precision)) / Math.pow(10, precision);
}
function mapFlyTo(long, lat) {
    map.flyTo({ center: [long, lat], zoom: 15 });
}

$('#showOpt').click(function () {
    $('#mapOptions').removeClass('d-none');
    $(this).addClass('d-none');
});
$('#hideOpt').click(function () {
    $('#mapOptions').addClass('d-none');
    $('#showOpt').removeClass('d-none');
});

$('#search').on("keyup click input", function () {
    var searchVal = $(this).val();
    if (searchVal.length >= $(this).attr('minlength')) {
        $.ajax({
            type: "POST",
            url: "modules/search.php",
            data: { term: searchVal },
            success: function (data) {
                $('#searchRes').html(data);
                $('#searchRes').addClass('show');
            }
        });
    } else {
        $('#searchRes').empty();
        $('#searchRes').removeClass('show');
    }
});
$(document).on('click', '#searchRes li', function () {
    var searchRes = $(this).text();
    $('#search').val(searchRes);
    $('#searchRes').empty().removeClass('show');
});