/*
 * LARAPACK
 * BY PAULO - PAULO@CLIQUETI.COM.BR
 * Versão 1.0
 */

/* Variaveis Globais */
var base_url = window.location.origin;

/* HTML AJAX LOAD */
var htmlAjaxLoad =  '<div class="ajax-load">\n' +
                    '    <div class="ajax-load-box">\n' +
                    '        <div class="ajax-load-box-circle"></div>\n' +
                    '        <p class="ajax-load-box-title">Aguarde, carregando...</p>\n' +
                    '    </div>\n' +
                    '</div>';

/* ************************
*          TOAST          *
* ************************/

function msgToast(messages){
    if(messages !== undefined && messages != ''){
        $.each(messages, function(k,message) {
            toastr.options = {
                positionClass    : "toast-top-right",
                progressBar      : true,
                preventDuplicates: false,
                showMethod       : 'slideDown',
                hideMethod       : 'slideUp',
                timeOut          : message.duration, //default timeout
                showDuration     : 300,
                hideDuration     : 500
            };
            toastr[message.type](message.text);
        });
    }
}

/* ***************************
* EXECUTA AÇÃO APOS UM TEMPO *
* ***************************/
function actionAfterTimeout(seconds,action,data) {
    window.setTimeout(function () {
        actions(action,data);
    }, seconds * 1000);
}

/* ****************************
* EXECUTA AÇÃO A CADA X TEMPO *
* ****************************/
function actionInterval(seconds,action,data) {
    window.setInterval(function () {
        actions(action,data);
    }, seconds * 1000);
}

/* **************************
*          TOOLTIP          *
* **************************/
if (typeof $.fn.tooltip === "function") {
    $('[data-toggle="tooltip"]').tooltip({title: function () { return ($(this).attr("placeholder") ?? $(this).attr("title")); }});
    $(".select2-container").tooltip({title: function () {  return $(this).prev().attr("placeholder"); }});
} else { console.log('Tooltip Ausente'); }

/* **************************
*   APLICA REGEX NO INPUT   *
* **************************/
$(document).on('keydown', 'input[pattern]', function(e){
    var input = $(this);
    var oldVal = input.val();
    var regex = new RegExp(input.attr('pattern'), 'g');

    setTimeout(function(){
        var newVal = input.val();
        if(!regex.test(newVal)){
            input.val(oldVal);
        }
    }, 1);
});

/* **************************
*          SELECT2          *
* **************************/
if (typeof $.fn.select2 === "function") {
    $('.select2').select2({
        theme: 'bootstrap4'
    });
} else { console.log('Select2 Ausente'); }

/* **************************
*    PREENCHE OUTRO SELECT  *
* **************************/
$('.load-select').change(function (){
    var parent_id = $(this).val();
    var toload = $(this).data('toload');
    var url = $(this).data('url');

    /* Limpa Registros Atuais */
    $('#'+toload).html('');
    $('#'+toload).append($('<option>', { value: 0, text: 'Aguarde Carregando...' }));
    /* REQUISITA DADOS */
    $.getJSON(url + parent_id, function (data) {
        $('#'+toload).html('');
        $('#'+toload).removeAttr('disabled');
        $.each(data, function (key, val) {
            $('#'+toload).append($('<option>', { value: key, text: val }));
        });
    });

});

/* ***************************
*       AJAX SELECT 2        *
* ***************************/
/**
 * CRIA COMPONENTES SELECT2 AJAX (PRECISA DO SELECT2 JQUERY)
 * @param objectID
 * @param url
 * @param placeholder
 */
function ajaxSelect2(objectID, url, placeholder,method='GET') {
    $("#"+objectID).select2({
        language: "pt-BR",
        placeholder: placeholder,
        minimumInputLength: 3,
        ajax: {
            url: url,
            dataType: "json",
            type: method,
            delay: 250,
            data: function(params) {
                return {
                    term: params.term,
                }
            },
            processResults: function(data) {
                return {
                    results: data
                }
            },
            cache: true
        }
    });
    $(".select2-container").tooltip({title: function () {  return $(this).prev().attr("placeholder"); }});
}


/* **************************
*          POPOVER          *
* **************************/
if (typeof $.fn.popover === "function") {
    $('[data-toggle="popover"]').popover();
} else { console.log('Popover Ausente'); }

/* ******************************
*          DATA TABLES          *
* ******************************/

/* Carrega Dados no DataTables */
function loadDataTable(url,tableId){
    $('#'+tableId).DataTable({
        "ajax": url,
        "language": {
            "url": base_url+"/vendor/datatables/locales/pt-br.json"
        },
        "responsive": true,
        "bAutoWidth": false,
        "order": [],
        createdRow: function(row, data) {
            //FancyBox
            if (typeof $('.fancybox-65').fancybox === "function") {
                $('.fancybox-65', row).fancybox({
                    'transitionIn'	: 'elastic',
                    'transitionOut'	: 'elastic',
                    'speedIn'		: 600,
                    'speedOut'		: 200,
                    'overlayShow'	: false,
                    type: 'iframe',
                    iframe : {
                        css : {
                            width  : '65vw',
                            height : '65vh'
                        }
                    }
                });
            }
        },
        "success": function (data) {
            data = data || [];
        }
    });
}

/* BASICS ELEMENTS OF AJAX */
$(document).ready(function(){
    $('body').append(htmlAjaxLoad);
});

/* ******************************
*          AUTO SEARCH          *
* ******************************/
$('.auto-search').blur(function(){
    console.log('Solicitando informações...')
    let url = $(this).data('url');
    let obj = $(this).data('obj');
    let csrf = $(this).data('csrf');
    let data = {term:$(this).val(),_token:(csrf??null)};
    /* POST */
    $.post(url, data, function (response) {
        /* Parse */
        response = JSON.parse(response);
        /* Mensagem */
        if(response.message){msgToast(response.message);}
        /* Ação */
        if(response.action){actions(response.action, response.data, obj);}
    }).fail(function(response) {
        console.log ('Oops... ', response);
    });
});

/* ***************************
*          AJAXLINK          *
* ***************************/
$(document).on('click','.ajax-link', function(e){
    e.preventDefault();
    /* VARS */
    var url = $(this).attr('href');
    url = (url??$(this).data('url'));
    var obj = $(this).data('obj') || "";
    /* GET */
    $.getJSON(url, function (response) {
        console.log('Sucesso!');
        /* Mensagem */
        if(response.message){msgToast(response.message);}
        /* Ação */
        if(response.action){actions(response.action, response.data, obj);}
    }).fail(function(response) {
        console.log ('Oops... ', response);
    });
});

/* ***************************
*          AJAXCHECK         *
* ***************************/
$(document).on('change','.ajax-check', function(){
    /* VARS */
    url = $(this).data('url');
    var obj = $(this).data('obj') || "";
    /* GET */
    $.getJSON(url, function (response) {
        console.log('Sucesso!');
        /* Mensagem */
        if(response.message){msgToast(response.message);}
        /* Ação */
        if(response.action){actions(response.action, response.data, obj);}
    }).fail(function(response) {
        console.log ('Oops... ', response);
    });
});

/* ***************************
*          AJAXFORM          *
* ***************************/
$("form:not('.ajax-off')").submit(function (e) {
    e.preventDefault();
    /* Variables */
    var form = $(this);
    const ajaxLoad = $('.ajax-load');
    /* Make the Request */
    form.ajaxSubmit({
        url: form.attr("action"),
        type: "POST",
        dataType: "json",
        beforeSend: function () {
            console.log('Enviando requisição...');
            ajaxLoad.fadeIn().css("display", "flex");
            $('.error-field').html('');
        },
        uploadProgress: function (event, position, total, completed) {
            var loaded = completed;
            var load_text = $(".ajax-load-box-title");
            load_text.text("Enviando (" + loaded + "%)");

            form.find("input[type='file']").val(null);
            if (completed >= 100) {
                load_text.text("Aguarde, carregando...");
            }
        },
        success: function (response) {
            /* Close Loading */
            ajaxLoad.fadeOut();
            /* Check Message */
            if(response.message){
                msgToast(response.message);
            }
            /* Check Action */
            if(response.action){
                actions(response.action, response.data, form);
            }
        },
        error: function (error) {
            ajaxLoad.fadeOut();
            /* Process Laravel Error */
            if(error.status == 422){
                /* Toast Message */
                console.log(error.responseJSON.message);
                /* Display errors on form field */
                $.each(error.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="'+i+'"]');
                    el.after($('<span class="span-error" style="color: #f33e3e;">'+error[0]+'</span>'));
                });
            }
        }
    });
});

/* **************************
*          ACTIONS          *
* **************************/
function actions(actions, data, obj) {
    if(actions){
        $.each(actions,function(action,value) {
            console.log('Ação:' + action);
            switch (action) {
                /* Limpa Formulário */
                case 'clearForm':
                    $(':input',obj)
                        .not(':button, :submit, :reset')
                        .val('')
                        .removeAttr('checked')
                        .removeAttr('selected');
                    break;
                /* Carrega Formulário */
                case 'loadForm':
                    console.log('Carregando Formulário: '+value);
                    $.each(data, function (k,v){
                        if($('#'+value+' #'+k).length == 1){
                            /* TEXT */
                            if($('#'+value+' #'+k)[0].type == 'text' || $('#'+value+' #'+k)[0].type == 'number' || $('#'+value+' #'+k)[0].type == 'date' || $('#'+value+' #'+k)[0].type == 'hidden' || $('#'+value+' #'+k)[0].type == 'select-one'){
                                $('#'+value+' #'+k).val(v);
                            }
                            /* CHECKBOX */
                            if($('#'+value+' #'+k)[0].type == 'checkbox'){
                                if(v == 0){
                                    $('#'+value+' #'+k).prop('checked', false);
                                }
                                if(v == 1){
                                    $('#'+value+' #'+k).prop('checked', true);
                                }
                            }
                            /* TEXTAREA */
                            if($('#'+value+' #'+k)[0].type == 'textarea'){
                                $('#'+value+' #'+k).text(v);
                            }
                            /* SELECT2 */
                            if($($('#'+value+' #'+k)[0]).hasClass('select2')){
                                $($('#'+value+' #'+k)[0]).trigger('change');
                            }
                        }
                    });
                    break;
                /* Insere no HTML */
                case 'insertInHtml':
                    console.log('Inserindo informações no html: '+value);
                    $.each(data, function (k,v){
                        if($('#'+value+' #'+k).length == 1){
                            $('#'+value+' #'+k).html(v);
                        }
                    });
                    break;
                /* Recarregar */
                case 'reload':
                    window.location.reload();
                    break;
                /* Redireciona */
                case 'redirect':
                    window.location = value;
                    break;
                /* Reload DataTable */
                case 'reloadDataTable':
                    console.log("Reload DataTable");
                    $('#'+value).DataTable().ajax.reload();
                    break;

                case 'closeModal':
                    console.log("Fechando Modal");
                    $('#'+value).modal('toggle');

                /* Padrão */
                default:
                    console.log('Ação não reconhecida:' + action);
            }
        })
    }
}

/* ***************************
*          FANCYBOX          *
* ***************************/
if (typeof $('.fancybox').fancybox === "function") {
    $('.fancybox').fancybox({
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic',
        'speedIn'		: 600,
        'speedOut'		: 200,
        'overlayShow'	: false,
        'type'			: 'iframe',
        iframe : {
            css : {
                borderRadius : '.30rem',
                overflow : 'hidden'
            }
        }
    });
    $('.fancybox-65').fancybox({
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic',
        'speedIn'		: 600,
        'speedOut'		: 200,
        'overlayShow'	: false,
        type: 'iframe',
        iframe : {
            css : {
                width  : '65vw',
                height : '65vh',
                borderRadius : '.30rem',
                overflow : 'hidden'
            }
        }
    });
    $('.fancybox-50').fancybox({
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic',
        'speedIn'		: 600,
        'speedOut'		: 200,
        'overlayShow'	: false,
        type: 'iframe',
        iframe : {
            css : {
                width  : '50vw',
                height : '50vh',
                borderRadius : '.30rem',
                overflow : 'hidden'
            }
        }
    });
    $('.fancybox-30').fancybox({
        'transitionIn'	: 'elastic',
        'transitionOut'	: 'elastic',
        'speedIn'		: 600,
        'speedOut'		: 200,
        'overlayShow'	: false,
        type: 'iframe',
        iframe : {
            css : {
                width  : '30vw',
                height : '30vh',
                borderRadius : '.30rem',
                overflow : 'hidden'
            }
        }
    });
}  else { console.log('FancyBox Ausente'); }

