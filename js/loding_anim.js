// JavaScript Document

jQuery(function($){

/* ----------------------------------------------------------
 ロゴアウトラインアニメーション
---------------------------------------------------------- */
var stroke;
stroke = new Vivus('mask', {//アニメーションをするIDの指定
    start:'manual',//自動再生をせずスタートをマニュアルに
    type: 'scenario-sync',// アニメーションのタイプを設定
    duration: 2,//アニメーションの時間設定。数字が小さくなるほど速い
    forceRender: false,//パスが更新された場合に再レンダリングさせない
    animTimingFunction:Vivus.EASE,//動きの加速減速設定
},
function(){
       $("#mask").attr("class", "done");//描画が終わったらdoneというクラスを追加
}
);

$(window).on('load',function(){
  $("#splash").delay(3000).fadeOut('800');//ローディング画面を3秒（3000ms）待機してからフェイドアウト
	$("#splash_logo").delay(3000).fadeOut('slow');//ロゴを3秒（3000ms）待機してからフェイドアウト
        stroke.play();//SVGアニメーションの実行
});



});
