let currentTopicFilter = 'all';
let currentSearchTerm = '';

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

    // check for search parameter in URL (to allow for searches from profile)
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    
    if (searchParam) {
        // Set the search input field value
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.value = searchParam;
        }
        
        // Apply the search filter
        searchPosts(searchParam);
    }

    //event handling for topic buttons

});

//clean up the search input and send to search function
function handleSearch(event){
    event.preventDefault();

    const searchInput = document.getElementById('search-input');
    const searchValue = searchInput.value.trim();

    searchPosts(searchValue);

    return false;
}

//live update the search
const searchInput = document.getElementById('search-input');
if (searchInput){
    searchInput.addEventListener('input', function() {
        searchPosts(this.value.trim());
    });
}

function searchPosts(key){
    currentSearchTerm = key.toLowerCase();
    applyFilters();
}

//function to filter posts by topic
function filterPosts(topic, event){
    // make sure only one button is active
    const topicButtons = document.querySelectorAll('.topic-button');
    topicButtons.forEach(button => {
        button.classList.remove('active');
    });

    // make the clicked button active
    event.target.classList.add('active');
    
    // save the current topic filter
    currentTopicFilter = topic.toLowerCase();
    
    // apply both filters
    applyFilters();
}

// helper function to apply search and topic filters at the same time
// (to prevent them overriding each other)
function applyFilters(){
    const posts = document.querySelectorAll('.post');
    //get search filter info
    const userFilter = document.getElementById("filter-users").checked;
    const postFilter = document.getElementById("filter-posts").checked;

    posts.forEach(post => {
        // get post title and topic
        const postTitle = post.querySelector('h2').textContent.toLowerCase();
        const postTopic = post.querySelector('.post-date').textContent.toLowerCase();
        const postUser = post.querySelector('.username').textContent.toLowerCase();
        
        // check if it matches topic filter
        const matchesTopic = (currentTopicFilter === 'all' || postTopic.includes(currentTopicFilter));
        
        // determine search matches based on checked filters
        let matchesSearch = false;
        
        // if no search term, everything matches
        if (currentSearchTerm === '') {
            matchesSearch = true;
        } else {
            // check title/content if post filter is on
            const titleMatches = postFilter && postTitle.includes(currentSearchTerm);
            
            // check username if user filter is on
            const userMatches = userFilter && postUser.includes(currentSearchTerm);
            
            // match if either condition is true based on selected filters
            matchesSearch = titleMatches || userMatches;
        }
        
        // only show if it matches both the topic filter and search criteria
        if (matchesTopic && matchesSearch) {
            post.style.display = 'block';
        } else {
            post.style.display = 'none';
        }
    });
}

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

// handle comment button click
function handleComments(postId) {
    const commentsSection = document.getElementById(`comments-section-${postId}`);
    
    // toggle visibility
    if (commentsSection.style.display === 'none') {
        commentsSection.style.display = 'block';
        loadComments(postId);
    } else {
        commentsSection.style.display = 'none';
    }
}

// fetch comments for a post
function loadComments(postId) {
    const commentsContainer = document.getElementById(`comments-container-${postId}`);
    
    // get comments via AJAX
    fetch(`get_comments.php?post_id=${postId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                commentsContainer.innerHTML = '<p class="no-comments">No comments yet.</p>';
            } else {
                // display comments
                commentsContainer.innerHTML = '';
                data.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.className = 'comment';
                    
                    const commentHeader = document.createElement('div');
                    commentHeader.className = 'comment-header';
                    
                    const username = document.createElement('span');
                    username.className = 'comment-username';
                    username.textContent = comment.username;
                    
                    const date = document.createElement('span');
                    date.className = 'comment-date';
                    date.textContent = comment.created_at;
                    
                    const commentText = document.createElement('p');
                    commentText.className = 'comment-text';
                    commentText.textContent = comment.content;
                    
                    commentHeader.appendChild(username);
                    commentHeader.appendChild(date);
                    commentElement.appendChild(commentHeader);
                    commentElement.appendChild(commentText);
                    
                    commentsContainer.appendChild(commentElement);
                });
            }
        })
        .catch(error => {
            commentsContainer.innerHTML = '<p class="error">Error loading comments. Please try again.</p>';
            console.error('Error getting comments:', error);
        });
}


//functions for other icons (TODO)
function handleLike(postId) {
    console.log('Like post:', postId);
    // implement like functionality
}

function handleDislike(postId) {
    console.log('Dislike post:', postId);
    // implement dislike functionality
}

function handleShare(postId) {
    console.log('Share post:', postId);
    // implement share functionality
}