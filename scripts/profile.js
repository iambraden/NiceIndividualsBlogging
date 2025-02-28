document.addEventListener("DOMContentLoaded", () => {
    const dropdownButton = document.querySelector(".dropdown-button");
    const dropdownContent = document.querySelector(".dropdown-content");

    dropdownButton.addEventListener("click", (event) => {
        event.stopPropagation();
        dropdownContent.classList.toggle("show");
    });

    document.addEventListener("click", () => {
        dropdownContent.classList.remove("show");
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