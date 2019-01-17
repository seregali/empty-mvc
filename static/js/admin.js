$(function () {

    $('.item-delete').click(function () {
        var title = $(this).closest('form').find('input[name="title"]').val().trim();

        if (!confirm("Удалить: "+title+"?"))
            return false;
    });

    $.hostname = "http://" + window.location.hostname;

    if ($('#fileupload').length) {
        initGallery();
        initFileUpload();
    }


});

function initGallery() {
    $("#gallery-images").sortable({
        stop:function (event, ui) {
            var ids = [];
            var orderings = [];
            $('.gallery-image').each(function (index) {
                $(this).find('.gallery-ordering').val(index);
                ids.push($(this).find('.gallery-image-delete').attr('data-id'));
            });

            $.post("image/setordering", {ids:ids});
        }
    });

    $('.gallery-image-delete').unbind('click').click(function () {
        if (!confirm('Удалить?'))
            return;

        var id = $(this).attr('data-id');
        $.post("image/delete", {id:id}, function () {
            $('#image' + id).remove();
        });
    });
}

function initFileUpload() {

    if ($('#fileupload').length>0) {
            $('#fileupload').fileupload({
            dataType:'json',
            acceptFileTypes:/(\.|\/)(gif|jpe?g|png)$/i,
            done:function (e, data) {
                var fileName = data.result[0];
                var folder = $('#object-id').data('folder');
                $.post('image/add', {objectId:$('#object-id').val(), url:fileName, folder: folder}, function (i) {
                    var id = $.trim(i);
                    $('#gallery-images').append('<div class="gallery-image" id="image'+id+'"><input type="hidden" name="image[]" value="'+id+'"/><img class="img-polaroid" src="image/resize?src=/uploads/'+folder+'/'+fileName+'&zc=2"/><span class="gallery-image-delete" data-id="'+id+'"><i>X</i></span></div>');

                    initGallery();
                });
            },
            formData:{entity:$('#object-id').data('folder')}
        });
    }

}
