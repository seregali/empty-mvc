$(function () {
    initItemSort();
});

function initItemSort() {
    $('.row, ul')
        .sortable({
            delay: 150,
            axis: "y",
            placeholder: "col-xs-12 placeholder",
            start: function (e, ui) {
                $('.col-xs-12.placeholder').css('height', ui.item.height());
            },
            update: function (event, ui) {
                var item = ui.item;
                var data = [];
                item.parent().children().each(function () {
                    data.push($(this).attr('data-id'));
                });

                $.post('article/sort', {data: data});
            }
        })
        .disableSelection();
}