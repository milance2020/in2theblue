<div class="meni">

    <div class="meni-header">

        <span class="meni-tag">
            MENU
        </span>

        <h2>
            Meni našeg bara 🍸
        </h2>

        <p>
            Pregledajte našu ponudu doručaka, kava, smoothieja,
            koktela i pića.
        </p>

    </div>

    <button class="meni-btn" onclick="togglePDF()">
        📖 Prikaži meni
    </button>

    <div id="pdfContainer" class="pdf-container">

        <iframe
            src="<?= URL_ASSETS_IMAGES_BAR ?>Menu2025.pdf"
        ></iframe>

    </div>

</div>


<script>
let isVisible = false;

function togglePDF() {

    const container =
        document.getElementById("pdfContainer");

    const btn =
        document.querySelector(".meni-btn");

    isVisible = !isVisible;

    container.style.display =
        isVisible ? "block" : "none";

    btn.textContent = isVisible
        ? "❌ Sakrij meni"
        : "📖 Prikaži meni";
}
</script>