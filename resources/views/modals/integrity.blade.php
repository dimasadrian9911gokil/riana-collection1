<div class="modal fade" id="integrityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold">Pernyataan Hukum</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Dengan mengklik tombol di bawah ini, saya setuju untuk...</p>
                </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-danger w-100 py-3 fw-bold" 
                    onclick="document.querySelector('input[name=agree_integrity]').checked=true; bootstrap.Modal.getInstance(document.getElementById('integrityModal')).hide();">
                    Saya Setuju
                </button>
            </div>
        </div>
    </div>
</div>