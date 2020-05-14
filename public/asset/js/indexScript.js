
let displayHeader = true;
changeHeight()
$(window).bind('resizeEnd', function () {
    console.log('change : ', $('body').height())
    changeHeight()
})

$(window).resize(function () {
    if (this.resizeTO) clearTimeout(this.resizeTO);
    this.resizeTO = setTimeout(function () {
        $(this).trigger('resizeEnd');
    }, 100);

});

$(".starRating").each(function(){
    console.log('starRating ->', $(this))
    $(this).html(starRating($(this).text()))
})
function starRating(value){
    console.log('starRating ->', value)
    let htmlCode = '';
    for(i=1 ;i<=5; i++){
        if(i <= value){

            htmlCode +='<i class="fas fa-star" style="color:goldenrod"></i>'
        }else if(i-1 < value){
            htmlCode +='<i class="fas fa-star-half-alt" style="color:goldenrod"></i>'
        }else{
            htmlCode +='<i class="far fa-star"></i>'
        }
        console.log(i, '\t', value, '\n--',htmlCode)
    }
    return htmlCode
}

// Bootstrap 4 Responsive Dropdown Multi Submenu
$(function(){
    $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show'); // appliqué au ul
        $(this).parent().toggleClass('show'); // appliqué au li parent

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass("show"); // appliqué au ul
            $('.dropdown-submenu.show').removeClass("show"); // appliqué au li parent
        });
        return false;
    });
});

// funsctions js

function changeHeight() {
    $("#content, body > section").css('min-height', ($('html').height() - ($('body > header').height() + $('body > footer').height() + 20)) + 'px')
    $("body").css('padding-bottum', $("body > footer").height() + 'px')
    $("body").css('padding-top', $("body > header").height() + 'px')
    //$("#navigationLeft").css('top', $("body > header").height() + 'px')



}

function changeDisplayHeader() {
    displayHeader = !displayHeader;
    const displayHeaderClass = displayHeader?'fas fa-level-up-alt':'fas fa-level-down-alt';
    $("#navHeader").css('display', displayHeader?'':'none');
    $("#displayHeader >i+i").attr('class', displayHeaderClass);
    changeHeight()
}

