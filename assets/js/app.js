// Preenche o modal de exclusão com o registro clicado
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('modalExcluir');
    if (modal) {
        modal.addEventListener('show.bs.modal', function (ev) {
            var botao = ev.relatedTarget;
            document.getElementById('modalExcluirId').value = botao.getAttribute('data-id');
            document.getElementById('modalExcluirNome').textContent = botao.getAttribute('data-nome');
        });
    }

    // Requisito 2.7: campos com a classe "so-numeros" aceitam apenas dígitos
    document.querySelectorAll('.so-numeros').forEach(function (campo) {
        campo.addEventListener('keydown', function (ev) {
            var permitidas = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End', 'Enter'];
            if (ev.ctrlKey || ev.metaKey || permitidas.indexOf(ev.key) !== -1) return;
            if (!/^[0-9]$/.test(ev.key)) ev.preventDefault();
        });
        campo.addEventListener('input', function () {
            var limpo = campo.value.replace(/\D/g, '');
            if (campo.value !== limpo) campo.value = limpo;
        });
    });

    // Filtro instantâneo dos atores no formulário de filme
    var buscaElenco = document.getElementById('buscaElenco');
    if (buscaElenco) {
        buscaElenco.addEventListener('input', function () {
            var termo = buscaElenco.value.toLowerCase();
            document.querySelectorAll('.item-ator').forEach(function (item) {
                item.style.display = item.textContent.toLowerCase().indexOf(termo) !== -1 ? '' : 'none';
            });
        });
    }
});
