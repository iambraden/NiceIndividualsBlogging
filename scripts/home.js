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
        const postHeader = post.querySelector(".post-header");

        const userIcon = document.createElement("img");
        userIcon.src = "../res/user.png";
        userIcon.alt = "User Icon";
        userIcon.classList.add("user-icon");

        postHeader.insertBefore(userIcon, postHeader.firstChild);

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
        if (event.key === "Escape" && popup && popup.style.display === "flex") {
            closeForm();
        }
    });
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