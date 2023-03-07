$(function () {
    //バリデーション
    var errors = false;
    function v_require(event) {
        let error;
        let _this = $('input.require');
        let value = _this.val();
        if (value == "") {
            error = true;
        }
        else if (!value.match(/[^\s\t]/)) {
            error = true;
        }

        if (error) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">お名前を入力してください。</p>');
                _this.addClass('error-input');
            }



        }
        else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('.error-info').remove();
                _this.removeClass('error-input');
            }
        }

    }

    function v_kana(event) {
        let error;
        let _this = $('input.v-kana');
        let value = _this.val();
        let etxt = '<p class = "error-info">お名前（フリガナ）を入力してください。</p>';
        if (value == "") {
            error = true;
        }
        else if (!value.match(/[^\s\t]/)) {
            error = true;
        }
        else if (!value.match(/^[ァ-ヶー　 ]+$/)) {
            error = true;
            etxt = '<p class = "error-info">お名前（フリガナ）は全角カタカナで入力してください。</p>';
        }

        if (error) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after(etxt);
                _this.addClass('error-input');
            }



        }
        else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('.error-info').remove();
                _this.removeClass('error-input');
            }
        }

    }

    function v_email(event) {
        let errortext;
        let _this = $('input.v-email');
        let value = _this.val();
        if (value == "" || !value.match(/[^\s\t]/)) {
            errortext = 'メールアドレスを入力してください。';
        }
        else if (!value.match(/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/)) {
            if (errortext) {
                errortext = '<br>メールアドレスが正しくありません。';
            } else {
                errortext = 'メールアドレスが正しくありません。';
            }

        }

        if (errortext) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">' + errortext + '</p>');
                _this.addClass('error-input');
            }

        }
        else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('p.error-info').remove();
                _this.removeClass('error-input');
            }
        }
    }

    function v_emailconf(event) {
        let errortext;
        let _this = $('input.v-emailconf');
        let v_email = $('input.v-email').val();
        let value = _this.val();

        if (value == "" || !value.match(/[^\s\t]/)) {
            errortext = 'メールアドレスを入力してください。';
        } else if (value != v_email) {
            errortext = 'メールアドレスが一致しません。';
        }


        if (errortext) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">' + errortext + '</p>');
                _this.addClass('error-input');
            } else {

                //消す
                _this.nextAll('p.error-info').remove();
                _this.removeClass('error-input');

                _this.after('<p class = "error-info">' + errortext + '</p>');
                _this.addClass('error-input');

            }

        }
        else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('p.error-info').remove();
                _this.removeClass('error-input');
            }
        }
    }
    function v_bland(event) {
        let error;
        let _this = $('#bland');
        let value = $('.v-bland option:selected').val();
        if (value == "") {
            error = true;
        }


        if (error) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">ブランドを選択してください。</p>');
                $('.v-bland').addClass('error-input');
                // _this.addClass('error-input');
            }


        }
        else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('.error-info').remove();
                $('.v-bland').removeClass('error-input');
            }
        }
    }

    function v_body(event) {
        let error;
        let _this = $('.v-body');
        let value = _this.val();
        if (value == "") {
            error = true;
        }


        if (error) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">お問い合わせ内容を入力してください。</p>');
                _this.addClass('error-input');
            }


        }
        else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('.error-info').remove();
                _this.removeClass('error-input');
            }
        }
    }

    //$('form').on('blur', v_require);
    //$('form').on('submit', v_email);
    //$('form').on('submit', v_emailconf);
    $('form').submit(function (event) {
        errors = false;
        // $('form').focuse();
        v_require(event);
        v_kana(event);
        v_email(event);
        v_emailconf(event);
        v_bland(event);
        v_body(event);
        if (errors) {
            let _this = $('.submit');
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">入力エラーがあります。</p>');
            }
            return false;
        }

    });


});