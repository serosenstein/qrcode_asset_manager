$(window).scroll(function() {
    var winScrollTop = $(window).scrollTop();
    var winHeight = $(window).height();
    var floaterHeight = $('#floater').outerHeight(true);
    var fromBottom = 20;
    var top = winScrollTop + winHeight - floaterHeight - fromBottom;
	$('#floater').css({'top': top + 'px'});
});
