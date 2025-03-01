document.addEventListener("DOMContentLoaded", () => {
    const dropdownButtons = document.querySelectorAll('.dropdown-button');
    const dropdownContents = document.querySelectorAll('.dropdown-content');

    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const dropdownContent = this.nextElementSibling;
            dropdownContent.classList.toggle('show');
        });
    });

    dropdownContents.forEach(content => {
        content.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });

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

    const posts = document.querySelectorAll(".post");
    posts.forEach(post => {
        const postHeader = post.querySelector(".post-header");

        const userIcon = document.createElement("img");
        userIcon.src = "res/user.png";
        userIcon.alt = "User Icon";
        userIcon.classList.add("user-iconPost");

        postHeader.insertBefore(userIcon, postHeader.firstChild);

        const postIcons = document.createElement("div");
        postIcons.classList.add("post-icons");

        const thumbsUp = document.createElement("img");
        thumbsUp.src = "res/thumbs-up.png";
        thumbsUp.alt = "Thumbs Up";
        thumbsUp.classList.add("icon");

        const thumbsDown = document.createElement("img");
        thumbsDown.src = "res/thumbs-down.png";
        thumbsDown.alt = "Thumbs Down";
        thumbsDown.classList.add("icon");

        const comment = document.createElement("img");
        comment.src = "res/comment-alt.png";
        comment.alt = "Comment";
        comment.classList.add("icon");

        const share = document.createElement("img");
        share.src = "res/share.png";
        share.alt = "Share";
        share.classList.add("icon");

        postIcons.appendChild(thumbsUp);
        postIcons.appendChild(thumbsDown);
        postIcons.appendChild(comment);
        postIcons.appendChild(share);

        post.appendChild(postIcons);
    });
});