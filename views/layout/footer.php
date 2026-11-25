</main>

<footer class="text-center text-muted small py-4 border-top">
    CineDB · Trabalho Prático de Análise e Desenvolvimento de Sistemas · PHP + MySQL
</footer>

<!-- Modal de confirmação de exclusão (usado por todas as listagens) -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-danger"></i> Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Deseja realmente excluir <strong id="modalExcluirNome"></strong>?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" id="modalExcluirId">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Excluir</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
