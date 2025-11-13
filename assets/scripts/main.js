const searchTriggerBtn = document.getElementById("search-trigger-btn");
const searchCloseBtn = document.getElementById("search-close-btn");
const searchModal = document.getElementById("search-modal");

searchTriggerBtn.addEventListener("click", () => {
  searchModal.classList.add("active");
});
searchCloseBtn.addEventListener("click", () => {
  searchModal.classList.remove("active");
});


const postForm = document.getElementById("post-form");
const upload_input = document.getElementById("upload_input");
const image_view = document.getElementById("image_view");
const editor = document.getElementById("editor");
const quill = new Quill('#editor', {
  theme: "snow",
  placeholder: "Write post content here...",
  modules: {
    toolbar: [
      [{
        'header': [1, 2, 3, 4, 5, 6]
      }],
      ['bold', 'italic', 'underline'], // toggled buttons
      ['blockquote', 'code-block'],
      ['link', 'image', 'video'],
    ]
  }
});

upload_input.addEventListener("change", e => {
  const [file] = upload_input.files
  image_view.classList.remove("hidden");
  if (file) {
    image_view.src = URL.createObjectURL(file)
  }
});

postForm.addEventListener("submit", (e) => {
  postForm.querySelector("#body").value = quill.root.innerHTML;
})