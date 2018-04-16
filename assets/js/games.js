jQuery(document).ready(function () {
    var answer = jQuery(".list-group-item");
    var count = 1;

    answer.each(function () {
        $(this).text(count + '. ' + $(this).text());
        count++;
    })
});