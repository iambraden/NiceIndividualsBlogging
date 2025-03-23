document.addEventListener("DOMContentLoaded", () => {
    // hide the form popup on page load
    const popup = document.getElementById("postForm");
    if (popup) {
        popup.style.display = "none";
    }
    
    const dropdownButtons = document.querySelectorAll('.dropdown-button');
    const dropdownContents = document.querySelectorAll('.dropdown-content');

    // toggle dropdown content visibility on button click
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const dropdownContent = this.nextElementSibling;
            dropdownContent.classList.toggle('show');
        });
    });

    // prevent dropdown content from closing when clicked inside
    dropdownContents.forEach(content => {
        content.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });

    // close dropdowns when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.matches('.dropdown-button')) {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(dropdown => {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            });
        }
    });

    // add icons to each post
    const posts = document.querySelectorAll(".post");
    posts.forEach(post => {
        const postIcons = document.createElement("div");
        postIcons.classList.add("post-icons");

        const thumbsUp = document.createElement("img");
        thumbsUp.src = "../res/thumbs-up.png";
        thumbsUp.alt = "Thumbs Up";
        thumbsUp.classList.add("icon");

        const thumbsDown = document.createElement("img");
        thumbsDown.src = "../res/thumbs-down.png";
        thumbsDown.alt = "Thumbs Down";
        thumbsDown.classList.add("icon");

        const comment = document.createElement("img");
        comment.src = "../res/comment-alt.png";
        comment.alt = "Comment";
        comment.classList.add("icon");

        const share = document.createElement("img");
        share.src = "../res/share.png";
        share.alt = "Share";
        share.classList.add("icon");

        postIcons.appendChild(thumbsUp);
        postIcons.appendChild(thumbsDown);
        postIcons.appendChild(comment);
        postIcons.appendChild(share);

        post.appendChild(postIcons);
    });
    
    // setup popup form event listeners
    if (popup) {
        popup.addEventListener("click", function(event) {
            if (event.target === popup) {
                closeForm();
            }
        });
    }
    
    // add escape key listener to close the form
    document.addEventListener("keydown", function(event) {
        const postForm = document.getElementById("postForm");
        const editForm = document.getElementById("editPostForm");
        
        if (event.key === "Escape") {
            if (postForm && postForm.style.display === "flex") {
                closeForm();
            }
            if (editForm && editForm.style.display === "flex") {
                closeEditForm();
            }
        }
    });

    // handle post options dropdown menus
    const postOptionsBtns = document.querySelectorAll('.post-options-btn');
    postOptionsBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            // close all other dropdowns first
            document.querySelectorAll('.post-options').forEach(options => {
                if (options !== this.parentElement) {
                    options.classList.remove('active');
                }
            });
            // toggle this dropdown
            this.parentElement.classList.toggle('active');
        });
    });

    // close post options when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.post-options').forEach(options => {
            options.classList.remove('active');
        });
    });
    
    // add click outside to close edit form too
    const editForm = document.getElementById("editPostForm");
    if (editForm) {
        editForm.addEventListener("click", function(event) {
            if (event.target === editForm) {
                closeEditForm();
            }
        });
    }

    // Automatically hide success and error messages after 5 seconds
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0'; 
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 500);
        }, 3000);
    }

    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = '0'; 
            setTimeout(() => {
                errorMessage.style.display = 'none'; 
            }, 500);
        }, 3000); 
    }
});


// functions for the post creation popup
function openForm() {
    console.log("openForm function called");
    const postForm = document.getElementById("postForm");
    console.log("postForm element:", postForm);
    
    if (postForm) {
        postForm.style.display = "flex";
        postForm.style.alignItems = "center";
        postForm.style.justifyContent = "center";
        document.body.style.overflow = "hidden";
        console.log("Popup should be visible now");
    }
}

function closeForm() {
    const postForm = document.getElementById("postForm");
    if (postForm) {
        postForm.style.display = "none"; // directly set to none
        document.body.style.overflow = "auto"; // re-enable scrolling
        
        // clear form inputs
        const titleInput = document.querySelector("input[name='postTitle']");
        const bodyInput = document.querySelector("textarea[name='postBody']");
        if (titleInput) titleInput.value = "";
        if (bodyInput) bodyInput.value = "";
    }
}

// functions for edit and delete
function editPost(postId) {
    // get the post element
    const postElement = document.querySelector(`.post:has(button[onclick="editPost(${postId})"])`);
    
    if (!postElement) {
        // try an alternative selector for older browsers
        const posts = document.querySelectorAll('.post');
        for (let post of posts) {
            if (post.querySelector(`button[onclick="editPost(${postId})"]`)) {
                postElement = post;
                break;
            }
        }
    }
    
    if (postElement) {
        // extract post data
        const title = postElement.querySelector('h2').textContent;
        const content = postElement.querySelector('p:not(.post-date)').textContent;
        
        // fill the edit form
        document.getElementById('edit-post-id').value = postId;
        document.getElementById('editPostTitle').value = title;
        document.getElementById('editPostBody').value = content;
        
        // show the edit form
        openEditForm();
    } else {
        console.error('Post element not found');
    }
}

function openEditForm() {
    const editForm = document.getElementById("editPostForm");
    if (editForm) {
        editForm.style.display = "flex";
        editForm.style.alignItems = "center";
        editForm.style.justifyContent = "center";
        document.body.style.overflow = "hidden";
    }
}

function closeEditForm() {
    const editForm = document.getElementById("editPostForm");
    if (editForm) {
        editForm.style.display = "none";
        document.body.style.overflow = "auto";
    }
}

function deletePost(postId) {
    if (confirm('Are you sure you want to delete this post?')) {
        console.log('Delete post:', postId);
        //DONE: switch from GET to POST for deletion

        // make a form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_post.php';
        form.style.display = 'none';
        
        // hidden input for post ID
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'postId';
        input.value = postId;

        // add redirect URL input
        const redirectInput = document.createElement('input');
        redirectInput.type = 'hidden';
        redirectInput.name = 'redirect_url';
        redirectInput.value = window.location.pathname;

        // add the input to the form, and add the form to the document
        form.appendChild(input);
        form.appendChild(redirectInput);
        document.body.appendChild(form);
        
        // submit the form to delete_post.php
        form.submit();
    }
}