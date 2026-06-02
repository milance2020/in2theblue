


async function loadAdminComments(status = 'pending') {

    try {
        const res = await fetch(`index.php?page=adminPanel&view=viewComments&action=get_admin_comments&status=${status}`);

        const data = await res.json();

       
        if (!data.success) {
            showToast(data.message, 'error');
            return;
        }



        //CONTAINER

        const container = document.querySelector('.admin-comments');

        if (!container) { return };



        //EMPTY

        if (data.comments.length === 0) {

            container.innerHTML = `
            <div>
                Nema komentara.
            </div>`;
            return;

        }

        //RENDER
        container.innerHTML = data.comments.map(renderAdminComment).join('');

    } catch (err) {
        console.error('load admin comments error', err);
    }

}


function renderAdminComment(comment) {

    return `
        <div class="admin-comment">

            <div class="admin-comment-header">

                <div class="admin-comment-user">

                    <strong>
                        ${escapeHtml(comment.username)}
                    </strong>

                </div>

                <div class="
                    admin-comment-status
                    ${comment.status}
                ">

                    ${escapeHtml(comment.status)}

                </div>

                ${comment.report_count > 0
                    ? `
                        <div class="
                            admin-comment-reports
                        ">
                            🚩 ${comment.report_count}
                        </div>
                    `
                    : ''
                }

            </div>


            <div class="admin-comment-body">

                ${escapeHtml(comment.comment)}

            </div>


            <div class="admin-comment-footer">

                <div class="admin-comment-reasons">

                    ${(comment.moderation_reasons || [])
                        .map(reason => `
                            <div class="
                                admin-comment-reason
                            ">
                                ${escapeHtml(reason)}
                            </div>
                        `)
                        .join('')
                    }

                </div>


                <div class="admin-comment-actions">

                    <button
                        class="approve-btn"
                        data-id="${comment.id}"
                    >
                        Odobri
                    </button>

                    <button
                        class="delete-btn"
                        data-id="${comment.id}"
                    >
                        Obriši
                    </button>

                </div>

            </div>

        </div>
    `;
}



function escapeHtml(text) {

    const div =
        document.createElement('div');

    div.innerText = text;

    return div.innerHTML;
}


//FILTERS
var status;
const filters = document.querySelector('.admin-comment-filters');

filters.addEventListener(
    'click',
    function (e) {
        const button = e.target.closest('button');

        if (!button) {
            return;
        }

        //GET STATUS

        status = button.dataset.status;
        



        loadAdminComments(status);
    }
)


document.addEventListener(
    'click',
    async function (e) {

        const deleteBtn =
            e.target.closest('.delete-btn');

        if (!deleteBtn) {
            return;
        }

        const commentId =
            deleteBtn.dataset.id;


        try {
            const res = await fetch(
                'index.php?page=adminPanel&view=viewComments&action=delete_comment',
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

            const data = await res.json();

           
            if (!data.success) {

                showToast(data.message, 'error');

                return;
            }
            showToast(
                'Komentar je uspješno obrisan.'
            );

            loadAdminComments(status);
        }catch(err){
            console.error('deleting comments error', err);
        }
    }
);

//APROVE COMMENT

document.addEventListener(
    'click',
    async function (e) {

        const aproveBtn =
            e.target.closest('.approve-btn');

        if (!aproveBtn) {
            return;
        }

        const commentId =
            aproveBtn.dataset.id;

        

        try {
            const res = await fetch(
                'index.php?page=adminPanel&view=viewComments&action=approve_comment',
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

            const data = await res.json();

            
            if (!data.success) {

                showToast(data.message, 'error');

                return;
            }

            showToast(
                'Komentar je sada vidljiv.'
            );

            loadAdminComments(status);
        }catch(err){
            console.error('aproving comments error', err);
        }
    }
);


function showToast(message, type = 'success') {

    const toastId = type === 'error' ? 'toast' : 'toast-green';
    const toast = document.getElementById(toastId);

    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}