// start geral do main
$(document).ready(() => {
    show_alerts();
    set_masks();


})

function show_alerts() {
    if (window.location.href.indexOf('success') != -1) {
        alert_success();
    }
    if (window.location.href.indexOf('error') != -1) {
        alert_error();
    }
    if (window.location.href.indexOf('campos-obrigatorios') != -1) {
        alert_custom('Campos obrigatórios não preenchidos!');
    }
    if (window.location.href.indexOf('login-inexistente') != -1) {
        alert_custom('Dados de login não encontrados!');
    }

    if (window.location.href.indexOf('restrito') != -1) {
        alert_custom('Você não tem autorização para realizar essa ação!');
    }
}

function alert_success() {
    $.toast("Ação realizada com sucesso!");
}

function alert_error() {
    $.toast("Não foi possível realizar essa ação!");
}

function alert_custom($msg = '') {
    $.toast($msg);
}

function set_masks() {
    $(".cpf").mask("999.999.999-99");
    $(".cnpj").mask("99.999.999/9999-99");
    $(".fone").mask("(99) 99999-9999");
}