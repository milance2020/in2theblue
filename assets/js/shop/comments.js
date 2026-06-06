// assets/js/shop/comments.js

// =========================================================
// COMMENTS CONTAINER
// =========================================================

const commentsContainer =
    document.querySelector('.comments');

const commentForm =

    document.querySelector('.comment-form');


// =========================================================
// REPLY 

// =========================================================


const comments = document.querySelector('.comments');

// Event delegation: dugmad za reply/report nastaju tek nakon rendera komentara.
comments.addEventListener(
    'click',
    function (e) {
        const replyBtn =
            e.target.closest('.reply-btn');

        if (!replyBtn) {
            return;
        }
        const commentID =
            replyBtn.dataset.id;

        const comment = replyBtn.closest('.comment');

        const replyContainer = comment.querySelector('.replies');

        replyContainer.innerHTML = `
             <form class="reply-form"
             data-parent-id="${commentID}">

            <textarea
                name = "reply"
                placeholder = "Napiši odgovor..."
            ></textarea>

                <button type="submit">
                    Odgovori
                </button>

             </form>`;


    }
);



comments.addEventListener(
    'submit',
    async function (e) {
        try {
            const replyForm =
                e.target.closest('.reply-form');

            if (!replyForm) {
                return;
            }

            e.preventDefault();


            const parentID = replyForm.dataset.parentId;


            const replyTextArea = replyForm.querySelector('textarea[name="reply"]');

            const reply = replyTextArea.value.trim();



            if (!reply) {
                return;
            }
            const productId =
                commentForm.dataset.id;


            const res = await fetch(
                `${window.location.pathname}?action=add_reply`,
                {
                    method: 'POST',
                    headers: {
                        'Content-Type':
                            'application/json'
                    },
                    body: JSON.stringify({
                        parent_id: parentID,
                        reply
                    })

                });

            const data = await res.json();

            if (!data.success) {

                showToast(
                    data.message,
                    'error'
                );

                return;
            }


            // =====================================================
            // VISIBLE
            // =====================================================

            if (data.status === 'visible') {

                showToast(
                    'Komentar uspješno dodan.'
                );

                replyForm.remove();

                loadComments(productId);

                return;
            }


            // =====================================================
            // PENDING
            // =====================================================

            if (data.status === 'pending') {

                showToast(
                    'Vaš komentar je na čekanju.'
                );

                replyForm.remove();

                return;
            }


            // =====================================================
            // HIDDEN
            // =====================================================

            if (data.status === 'hidden') {

                showToast(
                    'Vaš komentar nije mogao biti objavljen.',
                    'error'
                );
                replyForm.remove();
                return;
            }

        } catch (err) {
            showToast('Nešto je pošlo naopako.', 'error');

            console.error(
                'Reply submit error:',
                err
            );
        }
    }
)

// =========================================================
// REPORT COMMENT
// =========================================================

comments.addEventListener(
    'click',
    async function (e) {

        const reportBtn =
            e.target.closest('.report-btn');

        if (!reportBtn) {
            return;
        }

        try {

            const commentId =
                reportBtn.dataset.id;

            const productId =
                commentsContainer.dataset.productId;

            const res = await fetch(
                `${window.location.pathname}?action=add_report`,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json'
                    },

                    body: JSON.stringify({
                        comment_id: commentId
                    })
                }
            );

            const data =
                await res.json();


            // =================================================
            // API ERROR
            // =================================================

            if (!data.success) {

                showToast(
                    data.message,
                    'error'
                );

                return;
            }


            // =================================================
            // SUCCESS
            // =================================================

            showToast(
                'Komentar je prijavljen.'
            );


            // =================================================
            // OPTIONAL UI LOCK
            // =================================================

            reportBtn.disabled = true;

            reportBtn.classList.add(
                'reported'
            );


        } catch (err) {

            showToast(
                'Something went wrong.',
                'error'
            );

            console.error(
                'Report comment error:',
                err
            );
        }
    }
);

// =========================================================
// LOAD COMMENTS
// =========================================================

async function loadComments(productId) {

    try {

        const res = await fetch(
            `${window.location.pathname}?action=get_comments`
        );

        const data = await res.json();

        // =========================
        // API ERROR
        // =========================

        if (!data.success) {

            showToast(data.message, 'error');

            return;
        }

        // =========================
        // NO CONTAINER
        // =========================

        if (!commentsContainer) {
            return;
        }

        // =========================
        // EMPTY COMMENTS
        // =========================

        if (data.comments.length === 0) {

            commentsContainer.innerHTML = `
            <div class="no-comments">
                Nema komentara.
                </div>
            `;

            return;
        }

        // =========================
        // RENDER COMMENTS
        // =========================

        commentsContainer.innerHTML =
            data.comments
                .map(renderComment)
                .join('');

    } catch (err) {
        showToast('Nešto je pošlo naopako.', 'error');
        console.error(
            'Load comments error:',
            err
        );
    }
}

// =========================================================
// RENDER COMMENT
// =========================================================

function renderComment(comment) {
    const isLoggedIn =
        document.body.dataset.loggedIn === '1';

    return `
            <div class="comment">

            <div class="comment-header">

                <div class="comment-user">
                    ${escapeHtml(comment.username)}
                </div>

                <div class="comment-date">
                    ${formatDate(comment.created_at)}
                </div>
                <button class="report-btn"
                        data-id=${comment.id}>

                    🚩
                </button>

            </div>

            <div class="comment-body">

                <p>
                    ${escapeHtml(comment.comment)}
                </p>

            </div>

            <div class="comment-actions">
                
                ${isLoggedIn
            ? `
                            <button
                                class="reply-btn"
                                data-id="${comment.id}"
                                type="button"
                            >
                                Odgovori
                            </button>
                        `
            : ''
        }

            </div>

           <div class="replies">

    ${(comment.replies || [])
            .map(renderReply)
            .join('')
        }

</div>

        </div>
            `;
}




function formatDate(dateString) {

    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffSecs = Math.floor(diffMs / 1000);
    const diffMins = Math.floor(diffSecs / 60);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    // Ako je manje od minutu
    if (diffSecs < 60) {
        return 'Upravo sada';
    }

    // Ako je manje od sata
    if (diffMins < 60) {
        return `Prije ${diffMins} ${diffMins === 1 ? 'minut' : 'minuta'}`;
    }

    // Ako je manje od dana
    if (diffHours < 24) {
        return `Prije ${diffHours} ${diffHours === 1 ? 'sat' : 'sati'}`;
    }

    // Ako je manje od tjedna
    if (diffDays < 7) {
        return `Prije ${diffDays} ${diffDays === 1 ? 'dan' : 'dana'}`;
    }

    // Stariji komentari - prikaži datum i vrijeme
    return date.toLocaleDateString('hr-HR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}




function renderReply(reply) {

    return `
        <div class="comment reply">

            <div class="comment-header">

                <div class="comment-user">
                    ${escapeHtml(reply.username)}
                </div>

                <div class="comment-date">
                   ${formatDate(reply.created_at)}
                </div>
                <button class="report-btn"
                        data-id=${reply.id}>
                    🚩
                </button>

            </div>

            <div class="comment-body">

                <p>
                    ${escapeHtml(reply.comment)}
                </p>

            </div>

        </div>
    `;
}

// =========================================================
// COMMENT FORM SUBMIT
// =========================================================

if (commentForm) {


    commentForm.addEventListener(
        'submit',
        async function (e) {

            e.preventDefault();

            try {

                const productId =
                    this.dataset.id;

                const usernameInput =
                    this.querySelector(
                        '[name="username"]'
                    );

                const commentInput =
                    this.querySelector(
                        '[name="comment"]'
                    );

                const username =
                    usernameInput.value.trim();

                const comment =
                    commentInput.value.trim();

                // =====================
                // VALIDATION
                // =====================

                if (!username || !comment) {
                    return;
                }

                // =====================
                // SUBMIT
                // =====================

                const res = await fetch(
                    `${window.location.pathname}?action=add_comment`,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json'
                        },

                        body: JSON.stringify({
                            product_id: productId,
                            username,
                            comment
                        })
                    }
                );

                const data =
                    await res.json();

                // =====================
                // API ERROR
                // =====================

                if (!data.success) {

                    console.error(
                        data.message
                    );

                    return;
                }
                // =====================
                // CLEAR FORM
                // =====================

                commentInput.value = '';

                // =====================================================
                // VISIBLE
                // =====================================================

                if (data.status === 'visible') {

                    showToast(
                        'Komentar uspješno dodan.'
                    );



                    loadComments(productId);

                    return;
                }


                // =====================================================
                // PENDING
                // =====================================================

                if (data.status === 'pending') {

                    showToast(
                        'Vaš komentar je na čekanju'
                    );



                    return;
                }


                // =====================================================
                // HIDDEN
                // =====================================================

                if (data.status === 'hidden') {

                    showToast(
                        'Vaš komentar je na čekanju'
                    );

                    return;
                }








            } catch (err) {

                console.error(
                    'Comment submit error:',
                    err
                );
            }
        }



    );
}

// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHtml(text) {

    const div =
        document.createElement('div');

    div.innerText = text;

    return div.innerHTML;
}

// =========================================================
// AUTO LOAD COMMENTS
// =========================================================
if (commentsContainer) {

    const productId =
        commentsContainer.dataset.productId;

    loadComments(productId);
}

function showToast(message, type = 'success') {

    const toastId = type === 'error' ? 'toast' : 'toast-green';
    const toast = document.getElementById(toastId);

    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
