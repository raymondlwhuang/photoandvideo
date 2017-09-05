function getwidth(img, selection) {
    var scaleX = 100 / selection.width;
    var scaleY = 100 / selection.height;
    $('#cropx').val(selection.x1);
    $('#cropy').val(selection.y1);
//    $('#x2').val(selection.x2);
//    $('#y2').val(selection.y2);
    $('#cropwidth').val(selection.width);
    $('#cropheight').val(selection.height);    
}

$(function () {
    $('#image').imgAreaSelect({ handles: true, onSelectChange: getwidth });
});
