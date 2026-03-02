$(function () {
    //バリデーション
    var errors = false;
    //添付可能なMIME
    const mimetypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'text/plain',
        'text/csv',
        'application/pdf',
        'application/zip',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    var recaptchaSubmitting = false;
    var form = $('#form');
    var recaptchaTokenFieldSelector = 'input[name="g-recaptcha-response"]';



    form.submit(function (event) {
        errors = false;
        clear_recaptcha_error();
        // $('form').focuse();
        if ($('input.v-name').length) {
            v_name(event);
        }

        if ($('input.v-kana').length) {
            v_kana(event);
        }

        if ($('input.v-email').length) {
            v_email(event);
        }

        if ($('input.v-emailconf').length) {
            v_emailconf(event);
        }

        if ($('input.v-tel').length) {
            v_tel(event);
        }

        if ($('input.v-radio').length) {
            v_radio(event);
        }

        if ($('select.v-select').length) {
            v_select(event);
        }

        if ($('input.v-check').length) {
            v_check(event);
        }

        if ($('input.v-file').length) {
            v_file(event);
        }

        if ($('input.v-captcha').length) {
            v_captcha(event);
        }

        if ($('input.v-text').length) {
            v_text(event);
        }

        if ($('textarea.v-textarea').length) {
            v_textarea(event);
        }

        v_body(event);

        if (errors) {
            let _this = $('.submit');
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">入力エラーがあります。</p>');
            }
            return false;
        }

        if (form.find(recaptchaTokenFieldSelector).length) {
            if (recaptchaSubmitting) {
                recaptchaSubmitting = false;
                return true;
            }
            event.preventDefault();
            v_recaptcha(event);
            return false;
        }

    });

    function clear_recaptcha_error() {
        $('.v-recaptcha').find('p.error-info').remove();
    }

    function show_recaptcha_error(message) {
        let _this = $('.v-recaptcha');
        if (!_this.length) {
            return;
        }
        clear_recaptcha_error();
        _this.append('<p class = "error-info">' + message + '</p>');
    }


    function v_name(event) {
        let error = [];
        let _this = $('input.v-name');
        let value = _this.val();
        if (value == "" || !value.match(/[^\s\t]/)) {
            error.push('を入力してください。');
        } else if (value.length > 50) {
            error.push('は50文字以内で入力してください。');
        }

        if (error.length) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            let name = $('input[name="name_name"]').val();

            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                for (var i = 0; i < error.length; i++) {
                    _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                }
            } else {
                _this.nextAll('p.error-info').remove();
                for (var i = 0; i < error.length; i++) {
                    _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                }
            }
            _this.addClass('error-input');

        } else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('.error-info').remove();
                _this.removeClass('error-input');
            }
        }

    }

    function v_text(event) {

        let textobjs = $('input.v-text');

        textobjs.each(function (idx, elem) {
            let error = [];
            let _this = $(elem);
            let inputname = $(elem).attr('name');
            let value = $(elem).val();
            let name = $('input[name="' + inputname + '_name"]').val();

            if (value == "" || !value.match(/[^\s\t]/)) {
                error.push('を入力してください。');
            } else if (value.length > 50) {
                error.push('は50文字以内で入力してください。');
            }

            if (error.length) {
                //エラー時の処理
                errors = true;


                if (!_this.nextAll('p.error-info').length) {
                    //メッセージを後ろに追加
                    for (var i = 0; i < error.length; i++) {
                        _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                    }
                } else {
                    _this.nextAll('p.error-info').remove();
                    for (var i = 0; i < error.length; i++) {
                        _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                    }
                }
                _this.addClass('error-input');

            } else {
                //エラーじゃないのにメッセージがあったら
                //errors = false;
                if (_this.nextAll('p.error-info').length) {
                    //消す
                    _this.nextAll('.error-info').remove();
                    _this.removeClass('error-input');
                }
            }


        });



    }

    function v_kana(event) {
        let error = [];
        let _this = $('input.v-kana');
        let inputname = _this.attr('name');
        let value = _this.val();

        if (value == "" || !value.match(/[^\s\t]/)) {
            error.push('を入力してください。');
        } else if (!value.match(/^[ぁ-んー　 ]+$/)) {
            error.push('は全角ひらがなで入力してください。');
        } else if (value.length > 50) {
            error.push('は50文字以内で入力してください。');
        }

        if (error.length) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            let name = $('input[name="' + inputname + '_name"]').val();
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                for (var i = 0; i < error.length; i++) {
                    _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                }
            } else {
                _this.nextAll('p.error-info').remove();
                for (var i = 0; i < error.length; i++) {
                    _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                }
            }
            _this.addClass('error-input');

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
        let errortext = [];
        let _this = $('input.v-email');
        let value = _this.val();
        if (value == "" || !value.match(/[^\s\t]/)) {
            errortext.push('を入力してください。');
        }
        else if (!value.match(/^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/)) {
            errortext.push('が正しくありません。');
        } else if (value.length > 256) {
            errortext.push('は256文字以内で入力してください。');
        }

        if (errortext.length) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            let name = $('input[name="email_name"]').val();
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                for (var i = 0; i < errortext.length; i++) {
                    _this.after('<p class = "error-info">' + name + errortext[i] + '</p>');
                }
            } else {
                _this.nextAll('p.error-info').remove();
                for (var i = 0; i < errortext.length; i++) {
                    _this.after('<p class = "error-info">' + name + errortext[i] + '</p>');
                }
            }
            _this.addClass('error-input');

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
        let errortext = [];
        let _this = $('input.v-emailconf');
        let v_email = $('input.v-email').val();
        let value = _this.val();

        if (value == "" || !value.match(/[^\s\t]/)) {
            errortext.push('を入力してください。');
        } else if (value != v_email) {
            errortext.push('が一致しません。');
        } else if (value.length > 256) {
            errortext.push('は256文字以内で入力してください。');
        }


        if (errortext.length) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら

            let name = $('input[name="email_name"]').val();
            if (!_this.nextAll('p.error-info').length) {

                for (var i = 0; i < errortext.length; i++) {
                    _this.after('<p class = "error-info">' + name + '確認' + errortext[i] + '</p>');
                }
            } else {
                _this.nextAll('p.error-info').remove();
                for (var i = 0; i < errortext.length; i++) {
                    _this.after('<p class = "error-info">' + name + '確認' + errortext[i] + '</p>');
                }
            }
            _this.addClass('error-input');

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

    function v_tel(event) {
        let errortext = [];
        let _this = $('input.v-tel');
        let value = _this.val();
        if (value == "" || !value.match(/[^\s\t]/)) {
            errortext.push('を入力してください。');
        }
        else if (!value.match(/^0[0-9-]{9,13}$/)) {
            errortext.push('が正しくありません。');
        }

        if (errortext.length) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            let name = $('input[name="tel_name"]').val();
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                for (var i = 0; i < errortext.length; i++) {
                    _this.after('<p class = "error-info">' + name + errortext[i] + '</p>');
                }
            } else {
                _this.nextAll('p.error-info').remove();
                for (var i = 0; i < errortext.length; i++) {
                    _this.after('<p class = "error-info">' + name + errortext[i] + '</p>');
                }
            }
            _this.addClass('error-input');
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

    function v_radio(event) {
        let errortext = [];
        let radios = $('input.v-radio');
        //let radios = $('#form').find('input.v-radio');
        //console.log($('input.v-radio').last().attr('name'));
        const radionames = [];
        const labels = [];

        radios.each(function (idx, elem) {

            radionames.push($(elem).attr('name'));
        });

        const names = Array.from(new Set(radionames));

        names.forEach(function (nameval, index) {
            let _this = $('input[name="' + nameval + '"]').last().parent();
            if (!($('input[name="' + nameval + '"]:checked').length)) {
                //エラー時の処理
                errors = true;
                //エラーで、エラーメッセージがなかったら
                let name = $('input[name="' + nameval + '_name"]').val();
                if (!_this.nextAll('p.error-info').length) {
                    _this.after('<p class = "error-info">' + name + 'を入力してください。</p>');
                } else {
                    _this.nextAll('p.error-info').remove();
                    _this.after('<p class = "error-info">' + name + 'を入力してください。</p>');
                }
                $('input[name="' + nameval + '"]').addClass('error-input');

            } else {
                if (_this.nextAll('p.error-info').length) {
                    //消す
                    _this.nextAll('p.error-info').remove();
                    $('input[name="' + nameval + '"]').removeClass('error-input');
                }
            }
        });
    }

    function v_select(event) {
        let error;
        let selects = $('select.v-select');
        const selectnames = [];
        const labels = [];

        selects.each(function (idx, elem) {

            selectnames.push($(elem).attr('name'));
        });

        const names = Array.from(new Set(selectnames));

        names.forEach(function (nameval, index) {
            let _this = $('select[name="' + nameval + '"]');
            if (!($('select[name="' + nameval + '"]').val())) {
                //エラー時の処理
                errors = true;
                //エラーで、エラーメッセージがなかったら
                let name = $('input[name="' + nameval + '_name"]').val();
                if (!_this.nextAll('p.error-info').length) {
                    _this.after('<p class = "error-info">' + name + 'を入力してください。</p>');
                } else {
                    _this.nextAll('p.error-info').remove();
                    _this.after('<p class = "error-info">' + name + 'を入力してください。</p>');
                }
                $('input[name="' + nameval + '"]').addClass('error-input');

            } else {
                if (_this.nextAll('p.error-info').length) {
                    //消す
                    _this.nextAll('p.error-info').remove();
                    $('input[name="' + nameval + '"]').removeClass('error-input');
                }
            }
        });
    }

    function v_check(event) {
        let error;
        let selects = $('input.v-check');
        const checknames = [];

        selects.each(function (idx, elem) {

            checknames.push($(elem).attr('name'));
        });

        const names = Array.from(new Set(checknames));

        names.forEach(function (nameval, index) {
            let _this = $('input[name="' + nameval + '"]').last().parent();
            if (!($('input[name="' + nameval + '"]:checked').length)) {
                //エラー時の処理
                errors = true;
                //エラーで、エラーメッセージがなかったら
                let nametemp = nameval.replace("[]", "");
                let name = $('input[name="' + nametemp + '_name"]').val();
                if (!_this.nextAll('p.error-info').length) {
                    _this.after('<p class = "error-info">' + name + 'を入力してください。</p>');
                } else {
                    _this.nextAll('p.error-info').remove();
                    _this.after('<p class = "error-info">' + name + 'を入力してください。</p>');
                }
                $('input[name="' + nameval + '"]').addClass('error-input');

            } else {
                if (_this.nextAll('p.error-info').length) {
                    //消す
                    _this.nextAll('p.error-info').remove();
                    $('input[name="' + nameval + '"]').removeClass('error-input');
                }
            }
        });
    }

    function v_file(event) {
        let error;
        let limitsize = 1024 * 1024 * 2; //2M
        let _this = $('.v-file');
        let files = _this.prop('files').length;
        let fileobj = _this.prop('files')[0];



        if (!files) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">ファイルを選択してください。</p>');
                _this.addClass('error-input');
            } else {
                _this.nextAll('p.error-info').remove();
                _this.after('<p class = "error-info">ファイルを入力してください。</p>');
            }


        } else if (!fileobj.name.match(/^[A-Za-z0-9._-]*$/)) {
            errors = true;
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">ファイル名は半角英数でお願いします。</p>');
                _this.addClass('error-input');
            } else {
                _this.nextAll('p.error-info').remove();
                _this.after('<p class = "error-info">ファイル名は半角英数でお願いします。</p>');
            }
        } else if (!mimetypes.includes(fileobj.type)) {
            errors = true;
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">ファイル形式が正しくありません。</p>');
                _this.addClass('error-input');
            } else {
                _this.nextAll('p.error-info').remove();
                _this.after('<p class = "error-info">ファイル形式が正しくありません。</p>');
            }
        } else if (fileobj.size > limitsize) {
            errors = true;
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">ファイル容量は2M以下でお願いします。</p>');
                _this.addClass('error-input');
            } else {
                _this.nextAll('p.error-info').remove();
                _this.after('<p class = "error-info">ファイル容量は2M以下でお願いします。</p>');
            }
        } else {
            //エラーじゃないのにメッセージがあったら
            //errors = false;
            if (_this.nextAll('p.error-info').length) {
                //消す
                _this.nextAll('.error-info').remove();
                _this.removeClass('error-input');
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

    function v_textarea(event) {

        let textobjs = $('textarea.v-textarea');

        textobjs.each(function (idx, elem) {
            let error = [];
            let _this = $(elem);
            let inputname = $(elem).attr('name');
            let value = $(elem).val();
            let name = $('input[name="' + inputname + '_name"]').val();

            if (value == "" || !value.match(/[^\s\t]/)) {
                error.push('を入力してください。');
            } else if (value.length > 200) {
                error.push('は200文字以内で入力してください。');
            }

            if (error.length) {
                //エラー時の処理
                errors = true;


                if (!_this.nextAll('p.error-info').length) {
                    //メッセージを後ろに追加
                    for (var i = 0; i < error.length; i++) {
                        _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                    }
                } else {
                    _this.nextAll('p.error-info').remove();
                    for (var i = 0; i < error.length; i++) {
                        _this.after('<p class = "error-info">' + name + error[i] + '</p>');
                    }
                }
                _this.addClass('error-input');

            } else {
                //エラーじゃないのにメッセージがあったら
                //errors = false;
                if (_this.nextAll('p.error-info').length) {
                    //消す
                    _this.nextAll('.error-info').remove();
                    _this.removeClass('error-input');
                }
            }


        });



    }

    function v_recaptcha(event) {
        console.log('v_recaptcha start');
        let siteKey = String(form.data('recaptchaSiteKey') || '');
        if (!siteKey) {
            siteKey = String(form.find('input[name="g-recaptcha-site-key"]').val() || '');
        }
        if (!siteKey) {
            let scriptSrc = String($('#recaptcha-api-script').attr('src') || '');
            let match = scriptSrc.match(/[?&]render=([^&]+)/);
            if (match && match[1]) {
                siteKey = decodeURIComponent(match[1]);
            }
        }

        console.log('SiteKey found:', siteKey);

        let action = String(form.data('recaptchaAction') || 'contact_submit');
        if (!action) {
            action = String(form.find('input[name="g-recaptcha-action"]').val() || 'contact_submit');
        }
        if (!siteKey) {
            show_recaptcha_error('reCAPTCHAキーが設定されていません。');
            return false;
        }

        if (typeof grecaptcha === 'undefined' || typeof grecaptcha.ready !== 'function' || typeof grecaptcha.execute !== 'function') {
            console.error('grecaptcha object issue:', typeof grecaptcha);
            show_recaptcha_error('reCAPTCHAの読み込みに失敗しました。時間をおいて再度お試しください。');
            return false;
        }

        grecaptcha.ready(function () {
            console.log('grecaptcha ready. Executing...');
            grecaptcha.execute(siteKey, { action: action }).then(function (token) {
                console.log('Token received:', token ? 'YES' : 'NO');
                if (!token) {
                    show_recaptcha_error('reCAPTCHA認証に失敗しました。時間をおいて再度お試しください。');
                    return;
                }
                let input = form.find(recaptchaTokenFieldSelector);
                console.log('Input field found:', input.length);

                input.val(token);
                console.log('Token set to input. Submitting form...');

                recaptchaSubmitting = true;
                form.get(0).submit();
            }, function (err) {
                console.error('grecaptcha execute error:', err);
                show_recaptcha_error('reCAPTCHA認証に失敗しました。時間をおいて再度お試しください。');
            });
        });
        return true;
    }

    function v_captcha(event) {
        let error;
        let _this = $('.v-captcha');
        let value = _this.val();


        if (!value) {
            //エラー時の処理
            errors = true;
            //エラーで、エラーメッセージがなかったら
            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">画像認証を入力してください。</p>');
                _this.addClass('error-input');
            }


        } else if (!value.match(/^[0-9]{5}$/)) {
            errors = true;

            if (!_this.nextAll('p.error-info').length) {
                //メッセージを後ろに追加
                _this.after('<p class = "error-info">画像認証は半角数字5文字でお願いします。</p>');
                _this.addClass('error-input');
            } else {
                _this.nextAll('p.error-info').remove();
                _this.after('<p class = "error-info">画像認証は半角数字5文字でお願いします。</p>');
            }

        } else {
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



});
