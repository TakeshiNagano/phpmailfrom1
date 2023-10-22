// JavaScript Document

jQuery(function($){

/*-------------------------------------------
TOPスクロール TAB/SP
-------------------------------------------*/
$(function() {
    var pageTop = $('.totop');
    pageTop.hide();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 200) {
            pageTop.fadeIn();
        } else {
            pageTop.fadeOut();
        }
    });
    pageTop.click(function () {
        $('body, html').animate({scrollTop:0}, 500, 'swing');
        return false;
    });
});


/* ----------------------------------------------------------
 setViewport
---------------------------------------------------------- */
 $(function(){
        var ua = navigator.userAgent;
        if((ua.indexOf('iPhone') > 0) || ua.indexOf('iPod') > 0 || (ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0)){
            $('head').prepend('<meta name="viewport" content="width=device-width,initial-scale=1">');
        } else {
            $('head').prepend('<meta name="viewport" content="width=1600">');
        }
    });



/* ----------------------------------------------------------
 TELリンクチェッカーー用 
---------------------------------------------------------- */
$(function() {
    if (!isPhone())
        return;

    $('span[data-action=call]').each(function() {
        var $ele = $(this);
        $ele.wrap('<a href="tel:' + $ele.data('tel') + '"></a>');
    });
});

function isPhone() {
    return (navigator.userAgent.indexOf('iPhone') > 0 || navigator.userAgent.indexOf('Android') > 0);
}


/* ----------------------------------------------------------
 Webサイトにアニメーション
---------------------------------------------------------- */
var wow = new WOW(
  {
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       0,          // distance to the element when triggering the animation (default is 0)
    mobile:       false       // trigger animations on mobile devices (true is default)
  }
);
wow.init();

/* ----------------------------------------------------------
メニュー
---------------------------------------------------------- */
$(function () {
  $('.burger-btn').on('click', function () {
    $('.burger-btn').toggleClass('close');
    $('.menu_wrap').toggleClass('fade');
    $('body').toggleClass('noscroll');
  });
});

/*-----------------スマホタッチhover--------*/

//スマホタッチhover
$(function(){
    $( 'body')
      .bind( 'touchstart', function(){
        $( this ).addClass( 'hover' );
    }).bind( 'touchend', function(){
        $( this ).removeClass( 'hover' );
    });
});


window.addEventListener("scroll", function () {
  // ヘッダーを変数の中に格納する
  const header = document.querySelector("#header");
  // 100px以上スクロールしたらヘッダーに「scroll-nav」クラスをつける
  header.classList.toggle("scroll-nav", window.scrollY > 100);
});


/* ----------------------------------------------------------
 スクロール
---------------------------------------------------------- */
$('a[href^="#"]').click(function () {
    var speed = 400;
    var href = $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $('body,html').animate({ scrollTop: position }, speed, 'swing');
    return false;
});
 
 /* ----------------------------------------------------------
 スマホメニューでページ内リンクをタップするとメニューが閉じないとき
---------------------------------------------------------- */
$(function () {
    $(".menu_list li a").on("click", function () {
        $("body").toggleClass();
        $(".burger-btn").removeClass("close");
        $("body").removeClass("noscroll");
        $(".menu_wrap").removeClass("fade");
    });
});





/* ----------------------------------------------------------
 背景色が伸びて出現（左から右）
---------------------------------------------------------- */
// 動きのきっかけの起点となるアニメーションの名前を定義
function BgFadeAnime(){

    // 背景色が伸びて出現（左から右）
  $('.bgLRextendTrigger').each(function(){ //bgLRextendTriggerというクラス名が
    var elemPos = $(this).offset().top-50;//要素より、50px上の
    var scroll = $(window).scrollTop();
    var windowHeight = $(window).height();
    if (scroll >= elemPos - windowHeight){
      $(this).addClass('bgLRextend');// 画面内に入ったらbgLRextendというクラス名を追記
    }else{
      $(this).removeClass('bgLRextend');// 画面外に出たらbgLRextendというクラス名を外す
    }
  }); 

   // 文字列を囲う子要素
  $('.bgappearTrigger').each(function(){ //bgappearTriggerというクラス名が
    var elemPos = $(this).offset().top-50;//要素より、50px上の
    var scroll = $(window).scrollTop();
    var windowHeight = $(window).height();
    if (scroll >= elemPos - windowHeight){
      $(this).addClass('bgappear');// 画面内に入ったらbgappearというクラス名を追記
    }else{
      $(this).removeClass('bgappear');// 画面外に出たらbgappearというクラス名を外す
    }
  });   
}

// 画面をスクロールをしたら動かしたい場合の記述
  $(window).scroll(function (){
    BgFadeAnime();/* アニメーション用の関数を呼ぶ*/
  });// ここまで画面をスクロールをしたら動かしたい場合の記述

// 画面が読み込まれたらすぐに動かしたい場合の記述
  $(window).on('load', function(){
    BgFadeAnime();/* アニメーション用の関数を呼ぶ*/
  });// ここまで画面が読み込まれたらすぐに動かしたい場合の記述







});
