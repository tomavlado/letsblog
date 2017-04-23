$(function () {

    //buttons permission
    var btns = $('.btn-restrict');

    btns.attr('disabled', true);

    var body = document.body;
    var popup = document.createElement("div");
    popup.textContent = "You are not allowed to perform this action!";
    popup.setAttribute('class', 'popup-style');

    btns.on('click', function(){

        $(this).attr('href', '#');

        $(popup).insertBefore($(this).parent());

    });

    //drop-down admin menu
    var menu = $('.menu-head');
    var ul = $('.menu-options');

    menu.on('click', function (e) {

        e.stopPropagation();

        ul.slideToggle();
    });

    //open users messages modal
    ul.find('>:first-child').on('click', function () {
        $('#modal').modal('show').find('#usersMessages');
    });

    $('#back-button').on('click', function () {
       $('#modal').modal('show').find('#usersMessages');
    });

    //Set hiddenInput flag for title or tag search
    $('input[name=search-type]').on('change', function () {

        var searchField = $('input[name=search-info]');
        var elem = $(this).val();

        if(elem == 'item1'){
            searchField.attr('placeholder', 'search by title..');
            $('input[name=search-type-check]').val('1');
        }else{
            searchField.attr('placeholder', 'search by tag..');
            $('input[name=search-type-check]').val('2');
        }
    });

    //DropDownList options
    var ddList = $('.dd-list');
    var tagList = $('.tag-container');

    ddList.on('change', function () {
        var tagHolder = document.createElement('div');
        tagHolder.setAttribute('class', 'tag-holder');
        var selected = $('.dd-list option:selected').text();
        $('input[name=chosen-tag]').val(selected);
        tagHolder.setAttribute('id', selected);

        if(tagList.find('div').length > 2){
            alert('You can have most 3 tags!');
            return false;
        }

        if(tagList.find('#'+selected).length){
            return false;
        }else{
            tagHolder.append(selected);
            tagList.append(tagHolder);
        }
    });

});