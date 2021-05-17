let contentInner = document.querySelector(".content-inner");
let toggleBtn = document.querySelector("#toggle-btn");
let navSide = document.querySelector(".side-navbar");

toggleBtn.addEventListener("click", () => {
  console.log("test");
  contentInner.classList.toggle("active");
  navSide.classList.toggle("shrinked");
});
