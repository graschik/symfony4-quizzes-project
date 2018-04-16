var $collectionHolder;

jQuery(document).ready(function() {
    ajaxSearch();
    elementHandling();
    blurSearch();
});

function elementHandling() {
    $collectionHolder = $('ul.questions');

    $collectionHolder.find('li').each(function() {
        addQuestionFormDeleteLink($(this));
    });

    $collectionHolder = $('ul.questions');

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    checkChanges();
}

function $addQuestionForm($collectionHolder, $id, value) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, $id);

    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li></li>').append(newForm);
    $newFormLi.find('input').val(value);
    $collectionHolder.append($newFormLi);

    addQuestionFormDeleteLink($newFormLi);
}

function addQuestionFormDeleteLink($questionFormLi) {
    var $removeFormA = $(
        '<span class="input-group-btn disabled">' +
        '<button class="btn btn-danger btn-md delete" type="button">' +
        '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
        '</button>' +
        '</span>'
    );
    $questionFormLi.find('.input-group').append($removeFormA);

    $removeFormA.on('click', function(e) {

        e.preventDefault();

        if(!$(this).hasClass('disabled')){
            $questionFormLi.each(function () {
                $(this).remove();
                return false;
            });

            checkChanges();
        }
    });
}

function checkChanges() {
    setNumbers();
    checkRemoveButtonsAmount();
    checkRadioValue()
}

function setNumbers() {
    var $numbers = jQuery('.number');
    var $count = 0;
    $numbers.each(function () {
        $count++;
        $(this).text(''+$count);
    })
}

function checkRemoveButtonsAmount() {
    if($('.delete').length < 3){
        $('.delete').addClass('disabled');
        $('.input-group-btn').addClass('disabled');
    } else {
        $('.input-group-btn').removeClass('disabled');
        $('.disabled').removeClass('disabled');
    }

}

function checkRadioValue() {
    var $radioButtons = $('input[type="radio"]');

    $radioButtons.on('change', function (e) {
        $radioButtons.each(function () {
            if(this != e.target){
                $(this).prop('checked', false);
            }
        })
    })
}

function ajaxSearch() {
    var searchRequest = null;
    jQuery("#search").keyup(function() {
        var minlength = 1;
        var that = this;
        var value = jQuery(this).val();
        var entitySelector = jQuery("#entitiesNav").html('');
        entitySelector.hide();
        if (value.length >= minlength ) {
            if (searchRequest != null)
                searchRequest.abort();
                searchRequest = jQuery.ajax({
                type: "GET",
                url: "http://127.0.0.1:8000/ajax/front-controller",
                data: {
                    'route' : 'ajax.questions-search',
                    'value' : value
                },
                dataType: "text",
                success: function(msg){

                    if (value==jQuery(that).val()) {
                        var result = JSON.parse(msg);
                        jQuery.each(result, function(key, arr) {
                            jQuery.each(arr, function(id, value) {
                                if (key == 'entities') {
                                    if (id != 'error') {
                                        entitySelector.show();

                                        var listElement = $('<li><a class="question-'+id+'">'+value+'</a></li>');

                                        listElement.on('click', function(e) {
                                            e.preventDefault();
                                            $addQuestionForm($collectionHolder, id, value);

                                            entitySelector.hide();
                                            jQuery("#search").val('');
                                            entitySelector.empty();

                                            checkChanges();
                                        });

                                        entitySelector.append(listElement);
                                    } else {

                                    }
                                }
                            });
                        });
                    }
                }
            });
        } else {
            entitySelector.hide();
        }
    });
}

function blurSearch() {
    $(document).mouseup(function (e) {
        var container = $(".sidebar-search, #side-menu");
        if (container.has(e.target).length === 0){
            $('#entitiesNav').hide();
        }
    });
    $('#search').on('focusin', function () {
        if($('#entitiesNav').find('li').html() != null){
            $('#entitiesNav').show();
        }
    });
}