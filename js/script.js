// Initialize EasyMDE Markdown Editor
// Used on create-post.php and edit-post.php
const easyMDE = new EasyMDE({ 
    element: document.getElementById('markdown-editor'),
    placeholder: "Write your post using Markdown formatting...",
    minHeight: "250px",
    autosave: { enabled: false }
});
