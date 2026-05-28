const btn = document.getElementById('save-news');

if (btn) {
    btn.addEventListener('click', async function () {

        const id = this.dataset.id;
        const title = document.getElementById('news-title').value;
        const content = document.getElementById('news-content').value;

        const updateNewsUrl = window.APP_URLS?.adminUpdateNewsInline ?? 'model/admin/update_news_inline.php';
        const response = await fetch(updateNewsUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id, title, content })
        });

        const result = await response.json();

        if (result.success) {
            alert('Vijest sačuvana');
        } else {
            alert('Greška');
        }
    });
}



