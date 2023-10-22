jQuery(function(){
    jQuery('a[href^=#]').click(function() {
    var href= jQuery(this).attr("href");
    var target = jQuery(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top-130;
    jQuery('body,html').animate({scrollTop:position}, speed, 'swing');
    return false;
   });
});